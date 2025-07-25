<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        // Get total items count
        $totalItems = Inventory::count();

        // Get low stock items (quantity <= reorder_level but > 0)
        $lowStockItems = Inventory::where('quantity', '<=', DB::raw('reorder_level'))
            ->where('quantity', '>', 0)
            ->orderBy('item_name')
            ->get();
        $lowStockCount = $lowStockItems->count();

        // Get out of stock items (quantity = 0)
        $outOfStockCount = Inventory::where('quantity', 0)->count();

        // Get all inventory items for the table
        $inventoryItems = Inventory::orderBy('item_name')->with(['movements.user'])->get();

        // Mock data for recent deliveries (you can replace this with actual delivery data)
        $recentDeliveries = collect([
            (object) [
                'item_name' => 'Wheat Flour',
                'quantity' => '500 kg',
                'delivery_date' => '2024-01-15',
                'supplier' => 'ABC Suppliers'
            ],
            (object) [
                'item_name' => 'Sugar',
                'quantity' => '200 kg',
                'delivery_date' => '2024-01-14',
                'supplier' => 'XYZ Trading'
            ],
            (object) [
                'item_name' => 'Yeast',
                'quantity' => '50 kg',
                'delivery_date' => '2024-01-13',
                'supplier' => 'Fresh Ingredients Co.'
            ]
        ]);
        $recentDeliveriesCount = $recentDeliveries->count();

        // Calculate total inventory value
        $totalInventoryValue = $inventoryItems->sum(function($item) {
            return $item->quantity * $item->unit_price;
        });

        // Prepare data for stock level bar chart
        $stockLevelChartData = $inventoryItems->map(function($item) {
            return [
                'item_name' => $item->item_name,
                'quantity' => $item->quantity,
            ];
        });

        // Prepare data for stock status pie chart
        $statusCounts = [
            'in_stock' => $inventoryItems->where('quantity', '>', function($item) { return $item->reorder_level; })->count(),
            'low_stock' => $inventoryItems->where('quantity', '<=', function($item) { return $item->reorder_level; })->where('quantity', '>', 0)->count(),
            'out_of_stock' => $inventoryItems->where('quantity', 0)->count(),
        ];

        // Prepare movement trends (last 7 days)
        $movements = \App\Models\InventoryMovement::where('created_at', '>=', now()->subDays(7))->get();
        $dates = collect(range(0, 6))->map(function($i) { return now()->subDays(6 - $i)->format('Y-m-d'); });
        $stockInData = $dates->map(fn($date) => $movements->where('type', 'in')->where('created_at', '>=', $date.' 00:00:00')->where('created_at', '<=', $date.' 23:59:59')->sum('quantity'));
        $stockOutData = $dates->map(fn($date) => $movements->where('type', 'out')->where('created_at', '>=', $date.' 00:00:00')->where('created_at', '<=', $date.' 23:59:59')->sum('quantity'));

        // Fetch historical sales data (last 30 days, grouped by product and date)
        $salesHistory = \App\Models\OrderItem::select('product_name', 'quantity', 'created_at')
            ->where('created_at', '>=', now()->subDays(30))
            ->get()
            ->groupBy('product_name');
        $salesHistoryChartData = [];
        foreach ($salesHistory as $product => $items) {
            $daily = collect($items)->groupBy(function($item) {
                return \Carbon\Carbon::parse($item->created_at)->toDateString();
            })->map(function($group) {
                return $group->sum('quantity');
            });
            $salesHistoryChartData[$product] = $daily;
        }

        // Prepare sales data for 7-day forecast
        $salesData = \App\Models\OrderItem::select('product_name', 'quantity', 'created_at')
            ->where('created_at', '>=', now()->subMonths(6))
            ->get()
            ->map(function($item) {
                return [
                    'product_name' => strtolower($item->product_name),
                    'quantity' => $item->quantity,
                    'date' => $item->created_at->toDateString(),
                ];
            })->toArray();

        $forecastResponse = [];
        try {
            $response = \Illuminate\Support\Facades\Http::post('http://127.0.0.1:5000/predict', [
                'sales' => $salesData,
                'forecast_days' => 7
            ]);
            if ($response->successful()) {
                $forecastResponse = $response->json('forecast') ?? [];
            }
        } catch (\Exception $e) {
            $forecastResponse = [];
        }
        $salesForecastChartData = $forecastResponse;

        return view('admin.inventory.index', compact(
            'totalItems',
            'lowStockCount',
            'outOfStockCount',
            'inventoryItems',
            'lowStockItems',
            'recentDeliveries',
            'recentDeliveriesCount',
            'totalInventoryValue',
            'stockLevelChartData',
            'statusCounts',
            'dates',
            'stockInData',
            'stockOutData',
            'salesHistoryChartData',
            'salesForecastChartData'
        ));
    }
}
