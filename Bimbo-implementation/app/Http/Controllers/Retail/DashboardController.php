<?php

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $supplierInventory = \App\Models\Inventory::whereHas('user', function ($query) {
            $query->where('role', 'supplier');
        })->get();

        $lowStockItems = $supplierInventory->filter(function ($item) {
            return $item->needsReorder();
        });

        // Compute order analytics for the last 7 days
        $orderDays = [];
        $orderCounts = [];
        $userId = $user->id;
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $orderDays[] = $date;
            $orderCounts[] = \App\Models\Order::where('user_id', $userId)
                ->whereDate('created_at', $date)
                ->count();
        }

        // Compute order status breakdown for this user
        $statuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];
        $statusCounts = [];
        foreach ($statuses as $status) {
            $statusCounts[$status] = \App\Models\Order::where('user_id', $userId)
                ->where('status', $status)
                ->count();
        }

        $products = Product::all()->map(function($product) {
            $inventory = \App\Models\Inventory::where('item_name', $product->name)->first();
            $product->inventory_id = $inventory ? $inventory->id : null;
            return $product;
        });

        return view('dashboard.retail-manager', compact('supplierInventory', 'lowStockItems', 'orderDays', 'orderCounts', 'statusCounts', 'products'));
    }
} 