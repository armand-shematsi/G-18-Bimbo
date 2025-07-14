<?php

namespace App\Http\Controllers\Bakery;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventory items.
     */
    public function index(Request $request)
    {
        $query = Inventory::query();

        // Filter for bakery-relevant items only
        $query->whereIn('item_type', ['ingredient', 'finished_good', 'packaging']);

        // Filter by item type if specified
        if ($request->has('type') && $request->type !== '') {
            $query->where('item_type', $request->type);
        }

        // Filter by status if specified
        if ($request->has('status') && $request->status !== '') {
            switch ($request->status) {
                case 'low_stock':
                    $query->whereColumn('quantity', '<=', 'reorder_level');
                    break;
                case 'out_of_stock':
                    $query->where('quantity', 0);
                    break;
                case 'available':
                    $query->whereColumn('quantity', '>', 'reorder_level');
                    break;
            }
        }

        // Search by item name
        if ($request->has('search') && $request->search !== '') {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }

        $inventory = $query->orderBy('item_name')->paginate(15);

        // Get summary statistics for bakery items only
        $totalItems = Inventory::whereIn('item_type', ['ingredient', 'finished_good', 'packaging'])->count();
        $lowStockItems = Inventory::whereIn('item_type', ['ingredient', 'finished_good', 'packaging'])
            ->whereColumn('quantity', '<=', 'reorder_level')->count();
        $outOfStockItems = Inventory::whereIn('item_type', ['ingredient', 'finished_good', 'packaging'])
            ->where('quantity', 0)->count();
        $totalValue = Inventory::whereIn('item_type', ['ingredient', 'finished_good', 'packaging'])
            ->whereNotNull('unit_price')
            ->sum(DB::raw('quantity * unit_price'));

        // Get unique item types for filter (bakery items only)
        $itemTypes = Inventory::whereIn('item_type', ['ingredient', 'finished_good', 'packaging'])
            ->distinct()->pluck('item_type')->filter();

        return view('bakery.inventory.index', compact(
            'inventory',
            'totalItems',
            'lowStockItems',
            'outOfStockItems',
            'totalValue',
            'itemTypes'
        ));
    }

        /**
     * Show the form for creating a new inventory item.
     */
    public function create()
    {
        $products = Product::all();
        $itemTypes = ['ingredient', 'finished_good', 'packaging'];

        return view('bakery.inventory.create', compact('products', 'itemTypes'));
    }

    /**
     * Store a newly created inventory item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'unit' => 'required|string|max:50',
            'item_type' => 'required|string|max:100|in:ingredient,finished_good,packaging',
            'reorder_level' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $inventory = Inventory::create($validated);

        return redirect()->route('bakery.inventory.index')
            ->with('success', 'Inventory item created successfully.');
    }

    /**
     * Display the specified inventory item.
     */
    public function show(Inventory $inventory)
    {
        $movements = $inventory->movements()->orderBy('created_at', 'desc')->paginate(10);

        return view('bakery.inventory.show', compact('inventory', 'movements'));
    }

        /**
     * Show the form for editing the specified inventory item.
     */
    public function edit(Inventory $inventory)
    {
        $products = Product::all();
        $itemTypes = ['ingredient', 'finished_good', 'packaging'];

        return view('bakery.inventory.edit', compact('inventory', 'products', 'itemTypes'));
    }

    /**
     * Update the specified inventory item.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'unit' => 'required|string|max:50',
            'item_type' => 'required|string|max:100|in:ingredient,finished_good,packaging',
            'reorder_level' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $inventory->update($validated);

        return redirect()->route('bakery.inventory.index')
            ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified inventory item.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('bakery.inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    /**
     * Update stock quantity (for stock in/out operations).
     */
    public function updateStock(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity_change' => 'required|numeric',
            'movement_type' => 'required|in:in,out',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldQuantity = $inventory->quantity;

        if ($validated['movement_type'] === 'in') {
            $inventory->quantity += $validated['quantity_change'];
        } else {
            $inventory->quantity -= $validated['quantity_change'];
            if ($inventory->quantity < 0) {
                return back()->withErrors(['quantity_change' => 'Insufficient stock for this operation.']);
            }
        }

        $inventory->save();

        // Record the movement
        $inventory->movements()->create([
            'quantity' => $validated['quantity_change'],
            'type' => $validated['movement_type'],
            'note' => $validated['notes'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('bakery.inventory.show', $inventory)
            ->with('success', 'Stock updated successfully.');
    }

    /**
     * Get inventory data for AJAX requests.
     */
    public function apiIndex(Request $request)
    {
        $query = Inventory::query();

        // Filter for bakery-relevant items only
        $query->whereIn('item_type', ['ingredient', 'finished_good', 'packaging']);

        if ($request->has('type')) {
            $query->where('item_type', $request->type);
        }

        if ($request->has('status')) {
            switch ($request->status) {
                case 'low_stock':
                    $query->whereColumn('quantity', '<=', 'reorder_level');
                    break;
                case 'out_of_stock':
                    $query->where('quantity', 0);
                    break;
            }
        }

        $inventory = $query->orderBy('item_name')->get();

        return response()->json($inventory);
    }

    /**
     * Get live data for a specific inventory item.
     */
    public function liveData($id)
    {
        $inventory = Inventory::findOrFail($id);
        $status = 'available';
        $statusClass = 'bg-green-100 text-green-800';

        if ($inventory->quantity == 0) {
            $status = 'Out of Stock';
            $statusClass = 'bg-red-100 text-red-800';
        } elseif ($inventory->quantity <= $inventory->reorder_level) {
            $status = 'Low Stock';
            $statusClass = 'bg-yellow-100 text-yellow-800';
        }

        return response()->json([
            'quantity' => $inventory->quantity,
            'unit' => $inventory->unit,
            'reorder_level' => $inventory->reorder_level,
            'status' => $status,
            'status_class' => $statusClass,
            'last_updated' => $inventory->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get recent orders affecting this inventory item.
     */
    public function recentOrders($id)
    {
        try {
            $inventory = Inventory::findOrFail($id);
            // Get recent orders that include this item
            $recentOrders = \App\Models\Order::whereHas('items', function($query) use ($inventory) {
                $query->where('product_id', $inventory->product_id)
                      ->orWhere('item_name', 'like', '%' . $inventory->item_name . '%');
            })
            ->with(['customer', 'items'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($order) use ($inventory) {
                $relevantItem = $order->items->first(function($item) use ($inventory) {
                    return $item->product_id == $inventory->product_id ||
                           stripos($item->item_name, $inventory->item_name) !== false;
                });

                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer ? $order->customer->name : 'N/A',
                    'quantity' => $relevantItem ? $relevantItem->quantity : 0,
                    'unit' => $inventory->unit,
                    'status' => $order->status,
                    'created_at' => $order->created_at->format('M d, Y H:i')
                ];
            })
            ->filter(function($order) {
                return $order['quantity'] > 0;
            });

            return response()->json([
                'orders' => $recentOrders->values()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading recent orders: ' . $e->getMessage());

            // Return empty orders if there's an error
            return response()->json([
                'orders' => []
            ]);
        }
    }

        /**
     * Get chart data for inventory analytics.
     */
    public function chartData($id)
    {
        try {
            $inventory = Inventory::findOrFail($id);
            \Log::info('Chart data requested for inventory: ' . $inventory->id);

            // Get stock level data for the last 30 days
            $stockLevels = $this->getStockLevelData($inventory);

            // Get movement data for the last 30 days
            $movements = $this->getMovementData($inventory);

            // Get status distribution
            $statusDistribution = $this->getStatusDistribution($inventory);

            // Get key metrics
            $keyMetrics = $this->getKeyMetrics($inventory);

            // Get trend analysis
            $trendAnalysis = $this->getTrendAnalysis($inventory);

            $response = [
                'stockLevels' => $stockLevels,
                'movements' => $movements,
                'statusDistribution' => $statusDistribution,
                'keyMetrics' => $keyMetrics,
                'trendAnalysis' => $trendAnalysis
            ];

            \Log::info('Chart data response:', $response);

            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error('Error loading chart data: ' . $e->getMessage());

            // Return fallback data if there's an error
            return response()->json([
                'stockLevels' => [
                    'labels' => ['Jan 1', 'Jan 2', 'Jan 3', 'Jan 4', 'Jan 5'],
                    'values' => [100, 100, 100, 100, 100],
                    'reorderLevels' => [50, 50, 50, 50, 50]
                ],
                'movements' => [
                    'labels' => ['Jan 1', 'Jan 2', 'Jan 3', 'Jan 4', 'Jan 5'],
                    'stockIn' => [0, 0, 0, 0, 0],
                    'stockOut' => [0, 0, 0, 0, 0]
                ],
                'statusDistribution' => [1, 0, 0],
                'keyMetrics' => [
                    'totalMovements' => 0,
                    'avgStockIn' => 0,
                    'avgStockOut' => 0,
                    'turnoverRate' => 0
                ],
                'trendAnalysis' => [
                    'stockTrend' => 'Stable',
                    'stockTrendValue' => 'No significant change',
                    'stockTrendIndicator' => 'text-gray-500',
                    'demandPattern' => 'Consistent',
                    'demandPatternValue' => 'Regular usage',
                    'demandPatternIndicator' => 'text-gray-500'
                ]
            ]);
        }
    }

    /**
     * Get stock level data for charting.
     */
    private function getStockLevelData(Inventory $inventory)
    {
        $dates = [];
        $values = [];
        $reorderLevels = [];

        // Generate last 30 days
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dates[] = $date->format('M d');

            // For now, use current quantity (in a real app, you'd store historical data)
            $values[] = $inventory->quantity;
            $reorderLevels[] = $inventory->reorder_level;
        }

        return [
            'labels' => $dates,
            'values' => $values,
            'reorderLevels' => $reorderLevels
        ];
    }

    /**
     * Get movement data for charting.
     */
    private function getMovementData(Inventory $inventory)
    {
        $dates = [];
        $stockIn = [];
        $stockOut = [];

        try {
            // Get movements for the last 30 days
            $movements = $inventory->movements()
                ->where('created_at', '>=', now()->subDays(30))
                ->get()
                ->groupBy(function($movement) {
                    return $movement->created_at->format('Y-m-d');
                });
        } catch (\Exception $e) {
            // If movements table doesn't exist, return empty data
            $movements = collect();
        }

        // Generate last 30 days
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            $dates[] = $date->format('M d');

            if (isset($movements[$dateKey])) {
                $dayMovements = $movements[$dateKey];
                $stockIn[] = $dayMovements->where('type', 'in')->sum('quantity');
                $stockOut[] = $dayMovements->where('type', 'out')->sum('quantity');
            } else {
                $stockIn[] = 0;
                $stockOut[] = 0;
            }
        }

        return [
            'labels' => $dates,
            'stockIn' => $stockIn,
            'stockOut' => $stockOut
        ];
    }

    /**
     * Get status distribution for pie chart.
     */
    private function getStatusDistribution(Inventory $inventory)
    {
        // Get all inventory items of the same type for comparison
        $sameTypeItems = Inventory::where('item_type', $inventory->item_type)->get();

        $available = $sameTypeItems->where('quantity', '>', 'reorder_level')->count();
        $lowStock = $sameTypeItems->where('quantity', '<=', 'reorder_level')->where('quantity', '>', 0)->count();
        $outOfStock = $sameTypeItems->where('quantity', 0)->count();

        return [$available, $lowStock, $outOfStock];
    }

    /**
     * Get key metrics for the inventory item.
     */
    private function getKeyMetrics(Inventory $inventory)
    {
        try {
            // Get movements for the last 30 days
            $movements = $inventory->movements()
                ->where('created_at', '>=', now()->subDays(30))
                ->get();
        } catch (\Exception $e) {
            // If movements table doesn't exist, return empty collection
            $movements = collect();
        }

        $totalMovements = $movements->count();
        $totalStockIn = $movements->where('type', 'in')->sum('quantity');
        $totalStockOut = $movements->where('type', 'out')->sum('quantity');

        $avgStockIn = $totalMovements > 0 ? round($totalStockIn / 30, 2) : 0;
        $avgStockOut = $totalMovements > 0 ? round($totalStockOut / 30, 2) : 0;

        // Calculate turnover rate (how many times inventory is used/replenished)
        $turnoverRate = $inventory->quantity > 0 ? round(($totalStockOut / $inventory->quantity) * 100, 1) : 0;

        return [
            'totalMovements' => $totalMovements,
            'avgStockIn' => $avgStockIn,
            'avgStockOut' => $avgStockOut,
            'turnoverRate' => $turnoverRate
        ];
    }

    /**
     * Get trend analysis for the inventory item.
     */
    private function getTrendAnalysis(Inventory $inventory)
    {
        try {
            // Get movements for analysis
            $recentMovements = $inventory->movements()
                ->where('created_at', '>=', now()->subDays(30))
                ->orderBy('created_at')
                ->get();
        } catch (\Exception $e) {
            // If movements table doesn't exist, return empty collection
            $recentMovements = collect();
        }

        // Stock Trend Analysis
        $stockTrend = 'Stable';
        $stockTrendValue = 'No significant change';
        $stockTrendIndicator = 'text-gray-500';

        if ($recentMovements->count() > 0) {
            $firstHalf = $recentMovements->take(15);
            $secondHalf = $recentMovements->skip(15);

            $firstHalfNet = $firstHalf->where('type', 'in')->sum('quantity') - $firstHalf->where('type', 'out')->sum('quantity');
            $secondHalfNet = $secondHalf->where('type', 'in')->sum('quantity') - $secondHalf->where('type', 'out')->sum('quantity');

            if ($secondHalfNet > $firstHalfNet + 5) {
                $stockTrend = 'Increasing';
                $stockTrendValue = '+' . round($secondHalfNet - $firstHalfNet, 1) . ' units';
                $stockTrendIndicator = 'text-green-600';
            } elseif ($secondHalfNet < $firstHalfNet - 5) {
                $stockTrend = 'Decreasing';
                $stockTrendValue = round($secondHalfNet - $firstHalfNet, 1) . ' units';
                $stockTrendIndicator = 'text-red-600';
            }
        }

        // Demand Pattern Analysis
        $demandPattern = 'Consistent';
        $demandPatternValue = 'Regular usage';
        $demandPatternIndicator = 'text-gray-500';

        $stockOutMovements = $recentMovements->where('type', 'out');
        if ($stockOutMovements->count() > 5) {
            $avgDailyOut = $stockOutMovements->sum('quantity') / 30;
            $variance = $stockOutMovements->map(function($movement) use ($avgDailyOut) {
                return pow($movement->quantity - $avgDailyOut, 2);
            })->avg();

            if ($variance > $avgDailyOut * 2) {
                $demandPattern = 'Variable';
                $demandPatternValue = 'High fluctuation';
                $demandPatternIndicator = 'text-yellow-600';
            } else {
                $demandPattern = 'Consistent';
                $demandPatternValue = round($avgDailyOut, 1) . ' units/day';
                $demandPatternIndicator = 'text-green-600';
            }
        }

        // Reorder Frequency Analysis
        $reorderFrequency = 'Normal';
        $reorderFrequencyValue = 'Standard cycle';
        $reorderFrequencyIndicator = 'text-gray-500';

        $lowStockPeriods = 0;
        $currentDate = now()->subDays(30);
        for ($i = 0; $i < 30; $i++) {
            $checkDate = $currentDate->copy()->addDays($i);
            // This is a simplified check - in a real app you'd have historical stock levels
            if ($inventory->quantity <= $inventory->reorder_level) {
                $lowStockPeriods++;
            }
        }

        if ($lowStockPeriods > 10) {
            $reorderFrequency = 'High';
            $reorderFrequencyValue = $lowStockPeriods . ' days low';
            $reorderFrequencyIndicator = 'text-red-600';
        } elseif ($lowStockPeriods > 5) {
            $reorderFrequency = 'Moderate';
            $reorderFrequencyValue = $lowStockPeriods . ' days low';
            $reorderFrequencyIndicator = 'text-yellow-600';
        } else {
            $reorderFrequency = 'Low';
            $reorderFrequencyValue = $lowStockPeriods . ' days low';
            $reorderFrequencyIndicator = 'text-green-600';
        }

        return [
            'stockTrend' => $stockTrend,
            'stockTrendValue' => $stockTrendValue,
            'stockTrendIndicator' => $stockTrendIndicator,
            'demandPattern' => $demandPattern,
            'demandPatternValue' => $demandPatternValue,
            'demandPatternIndicator' => $demandPatternIndicator,
            'reorderFrequency' => $reorderFrequency,
            'reorderFrequencyValue' => $reorderFrequencyValue,
            'reorderFrequencyIndicator' => $reorderFrequencyIndicator
        ];
    }
}
