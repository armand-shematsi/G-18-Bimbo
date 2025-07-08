<?php

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    public function index()
    {
        return view('retail.inventory');
    }

    public function supplierInventory()
    {
        $supplierInventory = \App\Models\Inventory::whereHas('user', function($query) {
            $query->where('role', 'supplier');
        })->with('user')->get();

        $lowStockItems = $supplierInventory->filter(function($item) {
            return $item->needsReorder();
        });

        return view('retail.supplier-inventory', compact('supplierInventory', 'lowStockItems'));
    }

    public function check()
    {
        $user = auth()->user();
        $inventory = \App\Models\Inventory::where('user_id', $user->id)->with('user')->get();

        // Total bread in stock (sum of quantity for bread items)
        $totalBreadInStock = $inventory->where('item_type', 'bread')->sum('quantity');

        // Today's deliveries (orders delivered today)
        $todaysDeliveries = \App\Models\Order::where('user_id', $user->id)
            ->whereDate('delivered_at', now()->toDateString())
            ->where('status', \App\Models\Order::STATUS_DELIVERED)
            ->count();

        // Today's sales (sum of order totals delivered today)
        $todaysSales = \App\Models\Order::where('user_id', $user->id)
            ->whereDate('delivered_at', now()->toDateString())
            ->where('status', \App\Models\Order::STATUS_DELIVERED)
            ->sum('total');

        // Reorder alerts (count of inventory items at or below reorder level)
        $reorderAlerts = $inventory->filter(function($item) {
            return $item->needsReorder();
        })->count();

        return view('retail.inventory.check', compact('inventory', 'totalBreadInStock', 'todaysDeliveries', 'todaysSales', 'reorderAlerts'));
    }
}
