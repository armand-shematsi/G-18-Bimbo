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
        $inventoryItems = Inventory::orderBy('item_name')->get();

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

        return view('admin.inventory.index', compact(
            'totalItems',
            'lowStockCount',
            'outOfStockCount',
            'inventoryItems',
            'lowStockItems',
            'recentDeliveries',
            'recentDeliveriesCount'
        ));
    }
}
