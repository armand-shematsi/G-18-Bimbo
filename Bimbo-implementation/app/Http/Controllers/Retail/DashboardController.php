<?php

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\OrderReturn;
use App\Models\OrderItem;
use App\Models\RetailerOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        try {
            // Debug: Check what data we have
            $debugInfo = [
                'total_orders' => Order::count(),
                'orders_with_status' => Order::where('status', '!=', 'cancelled')->count(),
                'orders_today' => Order::whereDate('created_at', $today)->count(),
                'total_order_items' => OrderItem::count(),
                'inventory_items' => Inventory::where('location', 'retail')->count(),
                'auth_user_id' => auth()->id(),
                'auth_user_role' => auth()->user()->role ?? 'not set'
            ];

            // Calculate sales today (using Order model instead of RetailerOrder)
            $salesToday = Order::whereDate('created_at', $today)
                ->where('status', '!=', 'cancelled')
                ->sum('total') ?? 0;

            // Calculate orders today (using Order model instead of RetailerOrder)
            $ordersToday = Order::whereDate('created_at', $today)
                ->where('status', '!=', 'cancelled')
                ->count();

            // Calculate inventory value (for retail inventory)
            $inventoryValue = Inventory::where('location', 'retail')
                ->whereNotNull('unit_price')
                ->sum(DB::raw('COALESCE(quantity, 0) * COALESCE(unit_price, 0)')) ?? 0;

            // Calculate low stock count (for retail inventory)
            $lowStockCount = Inventory::where('location', 'retail')
                ->where('quantity', '<=', DB::raw('COALESCE(reorder_level, 0)'))
                ->count();

            // Calculate pending orders (using Order model instead of RetailerOrder)
            $pendingOrders = Order::where('status', 'pending')
                ->count();

            // Calculate returns today
            $returnsToday = OrderReturn::whereDate('created_at', $today)
                ->sum('refund_amount') ?? 0;

            // Get top-selling products (last 30 days) - using Order model
            $topSellingProducts = OrderItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as sold'))
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.status', '!=', 'cancelled')
                ->whereDate('orders.created_at', '>=', $today->copy()->subDays(30))
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

            // Inventory Trends (last 7 days)
            $inventoryTrends = collect();
            for ($i = 6; $i >= 0; $i--) {
                $date = $today->copy()->subDays($i);
                $total = Inventory::where('location', 'retail')
                    ->sum(DB::raw('COALESCE(quantity, 0)'));
                $inventoryTrends->push([
                    'date' => $date->toDateString(),
                    'total' => $total
                ]);
            }

            // Fetch bread orders (all) - using Order model
            $breadOrders = Order::whereHas('items', function($query) {
                $query->where('product_name', 'like', '%bread%');
            })
            ->with(['items' => function($query) {
                $query->where('product_name', 'like', '%bread%');
            }])
            ->get();

            // Bread order trends (last 7 days)
            $breadOrderTrends = collect();
            for ($i = 6; $i >= 0; $i--) {
                $date = $today->copy()->subDays($i)->toDateString();
                $count = $breadOrders->where('created_at', '>=', $date . ' 00:00:00')
                    ->where('created_at', '<=', $date . ' 23:59:59')
                    ->count();
                $breadOrderTrends->push([
                    'date' => $date,
                    'count' => $count
                ]);
            }

            // Debug variables for dashboard
            $totalOrders = Order::where('status', '!=', 'cancelled')->count();
            $todayOrders = Order::whereDate('created_at', $today)->where('status', '!=', 'cancelled')->count();

        } catch (\Exception $e) {
            // Fallback values if there's an error
            $salesToday = 0;
            $ordersToday = 0;
            $inventoryValue = 0;
            $lowStockCount = 0;
            $pendingOrders = 0;
            $returnsToday = 0;
            $topSellingProducts = collect();
            $inventoryTrends = collect();
            $breadOrders = collect();
            $breadOrderTrends = collect();
            $totalOrders = 0;
            $todayOrders = 0;
        }

        return view('dashboard.retail', compact(
            'salesToday',
            'ordersToday',
            'inventoryValue',
            'lowStockCount',
            'pendingOrders',
            'returnsToday',
            'topSellingProducts',
            'inventoryTrends',
            'breadOrders',
            'breadOrderTrends',
            'totalOrders',
            'todayOrders',
            'debugInfo'
        ));
    }
}
