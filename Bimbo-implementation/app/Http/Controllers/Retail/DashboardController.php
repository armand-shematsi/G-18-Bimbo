<?php

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\OrderReturn;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        try {
            // Calculate sales today
            $salesToday = Order::whereDate('placed_at', $today)
                ->where('status', '!=', 'cancelled')
                ->sum('total') ?? 0;

            // Calculate orders today
            $ordersToday = Order::whereDate('placed_at', $today)
                ->where('status', '!=', 'cancelled')
                ->count();

            // Calculate inventory value (only for current user's inventory)
            $inventoryValue = Inventory::where('user_id', auth()->id())
                ->whereNotNull('unit_price')
                ->sum(DB::raw('COALESCE(quantity, 0) * COALESCE(unit_price, 0)')) ?? 0;

            // Calculate low stock count
            $lowStockCount = Inventory::where('user_id', auth()->id())
                ->where('quantity', '<=', DB::raw('COALESCE(reorder_level, 0)'))
                ->count();

            // Calculate pending orders
            $pendingOrders = Order::where('status', 'pending')
                ->count();

            // Calculate returns today
            $returnsToday = OrderReturn::whereDate('created_at', $today)
                ->sum('refund_amount') ?? 0;

            // Get top-selling products (last 30 days)
            $topSellingProducts = OrderItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as sold'))
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.status', '!=', 'cancelled')
                ->whereDate('orders.placed_at', '>=', $today->copy()->subDays(30))
                ->groupBy('product_id', 'product_name')
                ->orderBy('sold', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'name' => $item->product_name ?: 'Unknown Product',
                        'sold' => $item->sold ?? 0
                    ];
                });

        } catch (\Exception $e) {
            // Fallback values if there's an error
            $salesToday = 0;
            $ordersToday = 0;
            $inventoryValue = 0;
            $lowStockCount = 0;
            $pendingOrders = 0;
            $returnsToday = 0;
            $topSellingProducts = collect();
        }

        return view('dashboard.retail', compact(
            'salesToday',
            'ordersToday',
            'inventoryValue',
            'lowStockCount',
            'pendingOrders',
            'returnsToday',
            'topSellingProducts'
        ));
    }
}
