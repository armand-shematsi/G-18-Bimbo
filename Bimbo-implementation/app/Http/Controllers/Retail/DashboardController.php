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
            // Calculate sales today
            $salesToday = RetailerOrder::whereDate('created_at', $today)
                ->where('status', '!=', 'cancelled')
                ->sum('total') ?? 0;

            // Calculate orders today
            $ordersToday = RetailerOrder::whereDate('created_at', $today)
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
            $pendingOrders = RetailerOrder::where('status', 'pending')
                ->count();

            // Calculate returns today
            $returnsToday = OrderReturn::whereDate('created_at', $today)
                ->sum('refund_amount') ?? 0;

            // Get top-selling products (last 30 days)
            $topSellingProducts = OrderItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as sold'))
                ->join('retailer_orders', 'order_items.order_id', '=', 'retailer_orders.id')
                ->where('retailer_orders.status', '!=', 'cancelled')
                ->whereDate('retailer_orders.created_at', '>=', $today->copy()->subDays(30))
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
                $total = Inventory::where('user_id', auth()->id())
                    ->sum(DB::raw('COALESCE(quantity, 0)'));
                $inventoryTrends->push([
                    'date' => $date->toDateString(),
                    'total' => $total
                ]);
            }

            // Fetch bread orders (all)
            $breadOrders = RetailerOrder::whereHas('items', function($query) {
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
            $totalOrders = RetailerOrder::where('status', '!=', 'cancelled')->count();
            $todayOrders = RetailerOrder::whereDate('created_at', $today)->where('status', '!=', 'cancelled')->count();

            // Debug: dump all retailer orders
            dd(\App\Models\RetailerOrder::all());

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
            'todayOrders'
        ));
    }
}
