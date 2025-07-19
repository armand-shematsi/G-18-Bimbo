<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use App\Models\InventoryMovement;
use App\Notifications\LowStockNotification;

class InventoryController extends Controller
{
    /**
     * Display the supplier's inventory.
     */
    public function index()
    {
        $inventory = Inventory::where('user_id', auth()->id())
            ->with(['movements' => function($query) {
                $query->latest()->limit(5);
            }])
            ->orderBy('item_name')
            ->get();

        // Calculate summary statistics
        $totalItems = $inventory->count();
        $totalValue = $inventory->sum(function($item) {
            return $item->quantity * $item->unit_price;
        });
        $lowStockItems = $inventory->where('status', 'low_stock')->count();
        $outOfStockItems = $inventory->where('status', 'out_of_stock')->count();

        return view('supplier.inventory.index', compact('inventory', 'totalItems', 'totalValue', 'lowStockItems', 'outOfStockItems'));
    }

    /**
     * Show the form for creating a new inventory item.
     */
    public function create()
    {
        return view('supplier.inventory.create');
    }

    /**
     * Store a newly created inventory item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_type' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'status' => 'required|in:available,low_stock,out_of_stock',
            'reorder_level' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $validated['user_id'] = auth()->id();

        $item = Inventory::create($validated);

        // Log initial stock-in movement
        InventoryMovement::create([
            'inventory_id' => $item->id,
            'user_id' => auth()->id(),
            'type' => 'in',
            'quantity' => $item->quantity,
            'note' => 'Initial stock-in on creation',
        ]);

        return redirect()->route('supplier.inventory.index')
            ->with('success', 'Inventory item added successfully.');
    }

    /**
     * Show the form for editing the specified inventory item.
     */
    public function edit($id)
    {
        $item = Inventory::where('user_id', auth()->id())->findOrFail($id);
        $movements = $item->movements()->with('user')->orderByDesc('created_at')->get();
        return view('supplier.inventory.edit', compact('item', 'movements'));
    }

    /**
     * Update the specified inventory item in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_type' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'status' => 'required|in:available,low_stock,out_of_stock',
            'reorder_level' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $validated['user_id'] = auth()->id();

        $item = Inventory::where('user_id', auth()->id())->findOrFail($id);
        $item->update($validated);

        return redirect()->route('supplier.inventory.index')
            ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified inventory item from storage.
     */
    public function destroy($id)
    {
        $item = Inventory::where('user_id', auth()->id())->findOrFail($id);
        $item->delete();

        return redirect()->route('supplier.inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    /**
     * Update the quantity of an inventory item.
     */
    public function updateQuantity(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $item = Inventory::where('user_id', auth()->id())->findOrFail($id);
        $oldQuantity = $item->quantity;
        $newQuantity = $validated['quantity'];
        $diff = $newQuantity - $oldQuantity;
        $type = $diff > 0 ? 'in' : ($diff < 0 ? 'out' : 'adjustment');

        $item->update($validated);

        if ($diff !== 0) {
            InventoryMovement::create([
                'inventory_id' => $item->id,
                'user_id' => auth()->id(),
                'type' => $type,
                'quantity' => abs($diff),
                'note' => 'Quantity updated by supplier',
            ]);
        }

        // Send low stock notification if now at or below reorder level
        if ($item->quantity <= $item->reorder_level) {
            // Notify supplier (current user)
            auth()->user()->notify(new LowStockNotification($item));
            // Notify admin (first admin user)
            $admin = \App\Models\User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new LowStockNotification($item));
            }
        }

        return redirect()->route('supplier.inventory.index')
            ->with('success', 'Inventory quantity updated successfully.');
    }

    /**
     * Show the inventory summary dashboard.
     */
    public function dashboard()
    {
        $userId = auth()->id();
        $inventory = \App\Models\Inventory::where('user_id', $userId)->get();
        $avgReorderLevel = $inventory->avg('reorder_level') ?: 50;
        $overstockThreshold = $avgReorderLevel * 3;

        $stats = [
            'total' => $inventory->count(),
            'available' => $inventory->where('status', 'available')->count(),
            'low_stock' => $inventory->where('status', 'low_stock')->count(),
            'out_of_stock' => $inventory->where('status', 'out_of_stock')->count(),
            'over_stock' => $inventory->where('quantity', '>', $overstockThreshold)->count(),
        ];

        // Total Stock In
        $totalStockIn = \App\Models\StockIn::where('user_id', $userId)->sum('quantity_received');
        // Total Stock Out
        $totalStockOut = \App\Models\StockOut::where('user_id', $userId)->sum('quantity_removed');

        // Daily Movement (last 7 days) using StockIn and StockOut (original logic)
        $stockInDaily = \App\Models\StockIn::where('user_id', $userId)
            ->where('received_at', '>=', now()->subDays(7))
            ->get()
            ->groupBy(function($item) { return \Carbon\Carbon::parse($item->received_at)->format('Y-m-d'); });
        $stockOutDaily = \App\Models\StockOut::where('user_id', $userId)
            ->where('removed_at', '>=', now()->subDays(7))
            ->get()
            ->groupBy(function($item) { return \Carbon\Carbon::parse($item->removed_at)->format('Y-m-d'); });
        $dates = collect(range(0, 6))->map(function($i) { return now()->subDays(6 - $i)->format('Y-m-d'); });
        $stockInData = $dates->map(fn($date) => isset($stockInDaily[$date]) ? $stockInDaily[$date]->sum('quantity_received') : 0);
        $stockOutData = $dates->map(fn($date) => isset($stockOutDaily[$date]) ? $stockOutDaily[$date]->sum('quantity_removed') : 0);

        // Top Selling Items (by quantity) - for current supplier's inventory items
        $topSelling = \App\Models\OrderItem::whereIn('product_name', $inventory->pluck('item_name'))
            ->select('product_name')
            ->selectRaw('SUM(quantity) as total_sold')
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Total inventory value - ensure we have valid data
        $totalInventoryValue = $inventory->sum(function($item) {
            $quantity = $item->quantity ?? 0;
            $unitPrice = $item->unit_price ?? 0;
            return $quantity * $unitPrice;
        });



        // Additional business metrics
        $totalReorderValue = $inventory->sum(function($item) {
            return $item->reorder_level * $item->unit_price;
        });

        // Calculate monthly trends (last 30 days)
        $monthlyStockIn = \App\Models\StockIn::where('user_id', $userId)
            ->where('received_at', '>=', now()->subDays(30))
            ->sum('quantity_received');

        $monthlyStockOut = \App\Models\StockOut::where('user_id', $userId)
            ->where('removed_at', '>=', now()->subDays(30))
            ->sum('quantity_removed');

        // Calculate inventory turnover rate
        $avgInventory = $inventory->avg('quantity') ?: 1;
        $inventoryTurnover = $monthlyStockOut / $avgInventory;

        // Calculate stock efficiency (items with good stock levels)
        $efficientStock = $inventory->filter(function($item) {
            return $item->quantity > $item->reorder_level && $item->quantity <= ($item->reorder_level * 2);
        })->count();

        // Stock level chart data
        $stockLevelChartData = $inventory->map(function($item) {
            return [
                'item_name' => $item->item_name,
                'quantity' => $item->quantity,
            ];
        });

        $lowStockItems = $inventory->filter(function($item) {
            return $item->quantity <= $item->reorder_level && $item->quantity > 0;
        });



        // Prepare for view
        return view('supplier.inventory.dashboard', [
            'stats' => $stats,
            'totalStockIn' => $totalStockIn,
            'totalStockOut' => $totalStockOut,
            'stockInData' => $stockInData,
            'stockOutData' => $stockOutData,
            'dates' => $dates,
            'inventory' => $inventory,
            'topSelling' => $topSelling,
            'totalInventoryValue' => $totalInventoryValue,
            'stockLevelChartData' => $stockLevelChartData,
            'lowStockItems' => $lowStockItems,
            // Additional business metrics
            'totalReorderValue' => $totalReorderValue,
            'monthlyStockIn' => $monthlyStockIn,
            'monthlyStockOut' => $monthlyStockOut,
            'inventoryTurnover' => round($inventoryTurnover, 2),
            'efficientStock' => $efficientStock,
            'overstockThreshold' => $overstockThreshold,
        ]);
    }

    /**
     * Display the specified inventory item with detailed stock movements.
     */
    public function show($id)
    {
        $item = Inventory::where('user_id', auth()->id())
            ->with(['movements' => function($query) {
                $query->orderByDesc('created_at');
            }])
            ->findOrFail($id);

        // Get stock in/out history
        $stockInHistory = \App\Models\StockIn::where('user_id', auth()->id())
            ->where('inventory_id', $item->id)
            ->orderByDesc('received_at')
            ->get();

        $stockOutHistory = \App\Models\StockOut::where('user_id', auth()->id())
            ->where('inventory_id', $item->id)
            ->orderByDesc('removed_at')
            ->get();

        // Calculate statistics
        $totalStockIn = $stockInHistory->sum('quantity_received');
        $totalStockOut = $stockOutHistory->sum('quantity_removed');
        $currentStock = $item->quantity;
        $totalValue = $currentStock * $item->unit_price;

        // Get recent movements (last 10)
        $recentMovements = $item->movements()->with('user')->orderByDesc('created_at')->limit(10)->get();

        return view('supplier.inventory.show', compact(
            'item',
            'stockInHistory',
            'stockOutHistory',
            'totalStockIn',
            'totalStockOut',
            'currentStock',
            'totalValue',
            'recentMovements'
        ));
    }
}
