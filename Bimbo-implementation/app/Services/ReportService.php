<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Inventory;
use App\Models\ProductionBatch;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Generate daily report for specific stakeholder role
     */
    public function generateDailyReport(string $role): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        
        switch ($role) {
            case 'admin':
                return $this->generateAdminDailyReport($today, $yesterday);
            case 'supplier':
                return $this->generateSupplierDailyReport($today, $yesterday);
            case 'bakery_manager':
                return $this->generateBakeryManagerDailyReport($today, $yesterday);
            case 'distributor':
                return $this->generateDistributorDailyReport($today, $yesterday);
            case 'retail_manager':
                return $this->generateRetailManagerDailyReport($today, $yesterday);
            case 'customer':
                return $this->generateCustomerDailyReport($today, $yesterday);
            default:
                return [];
        }
    }

    /**
     * Generate weekly report for specific stakeholder role
     */
    public function generateWeeklyReport(string $role): array
    {
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();
        
        switch ($role) {
            case 'admin':
                return $this->generateAdminWeeklyReport($weekStart, $weekEnd, $lastWeekStart, $lastWeekEnd);
            case 'supplier':
                return $this->generateSupplierWeeklyReport($weekStart, $weekEnd, $lastWeekStart, $lastWeekEnd);
            case 'bakery_manager':
                return $this->generateBakeryManagerWeeklyReport($weekStart, $weekEnd, $lastWeekStart, $lastWeekEnd);
            case 'distributor':
                return $this->generateDistributorWeeklyReport($weekStart, $weekEnd, $lastWeekStart, $lastWeekEnd);
            case 'retail_manager':
                return $this->generateRetailManagerWeeklyReport($weekStart, $weekEnd, $lastWeekStart, $lastWeekEnd);
            case 'customer':
                return $this->generateCustomerWeeklyReport($weekStart, $weekEnd, $lastWeekStart, $lastWeekEnd);
            default:
                return [];
        }
    }

    /**
     * Get low stock items for inventory alerts
     */
    public function getLowStockItems(int $threshold = 10): \Illuminate\Support\Collection
    {
        return Inventory::where('quantity', '<=', $threshold)
                       ->with(['product', 'user'])
                       ->get();
    }

    /**
     * Generate admin daily report
     */
    private function generateAdminDailyReport(Carbon $today, Carbon $yesterday): array
    {
        return [
            'report_type' => 'admin_daily',
            'date' => $today->format('Y-m-d'),
            'summary' => [
                'total_orders' => Order::whereDate('created_at', $today)->count(),
                'total_revenue' => Order::whereDate('created_at', $today)->sum('total_amount'),
                'active_vendors' => Vendor::where('status', 'active')->count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
            ],
            'orders' => [
                'today' => Order::whereDate('created_at', $today)->count(),
                'yesterday' => Order::whereDate('created_at', $yesterday)->count(),
                'by_status' => Order::whereDate('created_at', $today)
                                   ->select('status', DB::raw('count(*) as count'))
                                   ->groupBy('status')
                                   ->pluck('count', 'status')
                                   ->toArray(),
            ],
            'production' => [
                'batches_completed' => ProductionBatch::whereDate('actual_end', $today)
                                                    ->where('status', 'Completed')
                                                    ->count(),
                'batches_in_progress' => ProductionBatch::where('status', 'In Progress')->count(),
            ],
            'inventory' => [
                'low_stock_items' => Inventory::where('quantity', '<=', 10)->count(),
                'out_of_stock_items' => Inventory::where('quantity', 0)->count(),
            ],
            'financial' => [
                'daily_revenue' => Order::whereDate('created_at', $today)->sum('total_amount'),
                'pending_payments' => Payment::where('status', 'pending')->sum('amount'),
            ],
        ];
    }

    /**
     * Generate supplier daily report
     */
    private function generateSupplierDailyReport(Carbon $today, Carbon $yesterday): array
    {
        return [
            'report_type' => 'supplier_daily',
            'date' => $today->format('Y-m-d'),
            'summary' => [
                'orders_received' => Order::whereDate('created_at', $today)
                                        ->where('status', 'pending')
                                        ->count(),
                'total_order_value' => Order::whereDate('created_at', $today)
                                          ->where('status', 'pending')
                                          ->sum('total_amount'),
            ],
            'inventory_status' => [
                'items_in_stock' => Inventory::where('quantity', '>', 0)->count(),
                'low_stock_items' => Inventory::where('quantity', '<=', 10)->count(),
                'items_needing_restock' => Inventory::where('quantity', '<=', 5)->get()
                                                   ->map(function ($item) {
                                                       return [
                                                           'product_name' => $item->product->name ?? 'Unknown',
                                                           'current_quantity' => $item->quantity,
                                                           'reorder_level' => $item->reorder_level ?? 10,
                                                       ];
                                                   }),
            ],
            'recent_orders' => Order::whereDate('created_at', $today)
                                   ->with(['items.product'])
                                   ->take(10)
                                   ->get()
                                   ->map(function ($order) {
                                       return [
                                           'order_id' => $order->id,
                                           'customer' => $order->customer_name ?? 'Unknown',
                                           'total' => $order->total_amount,
                                           'status' => $order->status,
                                           'items' => $order->items->count(),
                                       ];
                                   }),
        ];
    }

    /**
     * Generate bakery manager daily report
     */
    private function generateBakeryManagerDailyReport(Carbon $today, Carbon $yesterday): array
    {
        return [
            'report_type' => 'bakery_manager_daily',
            'date' => $today->format('Y-m-d'),
            'summary' => [
                'batches_scheduled' => ProductionBatch::whereDate('scheduled_start', $today)->count(),
                'batches_completed' => ProductionBatch::whereDate('actual_end', $today)
                                                    ->where('status', 'Completed')
                                                    ->count(),
                'batches_in_progress' => ProductionBatch::where('status', 'In Progress')->count(),
            ],
            'production_status' => [
                'today_batches' => ProductionBatch::whereDate('scheduled_start', $today)
                                                ->with(['product'])
                                                ->get()
                                                ->map(function ($batch) {
                                                    return [
                                                        'batch_name' => $batch->name,
                                                        'product' => $batch->product->name ?? 'Unknown',
                                                        'status' => $batch->status,
                                                        'scheduled_start' => $batch->scheduled_start,
                                                        'actual_start' => $batch->actual_start,
                                                    ];
                                                }),
                'upcoming_batches' => ProductionBatch::where('scheduled_start', '>', $today)
                                                   ->where('status', 'Scheduled')
                                                   ->take(5)
                                                   ->get()
                                                   ->map(function ($batch) {
                                                       return [
                                                           'batch_name' => $batch->name,
                                                           'scheduled_start' => $batch->scheduled_start,
                                                           'quantity' => $batch->quantity,
                                                       ];
                                                   }),
            ],
            'ingredient_status' => [
                'low_stock_ingredients' => Inventory::where('quantity', '<=', 10)
                                                   ->whereHas('product', function ($query) {
                                                       $query->where('type', 'ingredient');
                                                   })
                                                   ->get()
                                                   ->map(function ($item) {
                                                       return [
                                                           'ingredient_name' => $item->product->name ?? 'Unknown',
                                                           'current_quantity' => $item->quantity,
                                                       ];
                                                   }),
            ],
        ];
    }

    /**
     * Generate distributor daily report
     */
    private function generateDistributorDailyReport(Carbon $today, Carbon $yesterday): array
    {
        return [
            'report_type' => 'distributor_daily',
            'date' => $today->format('Y-m-d'),
            'summary' => [
                'orders_to_deliver' => Order::where('status', 'processing')->count(),
                'orders_delivered_today' => Order::whereDate('updated_at', $today)
                                               ->where('status', 'shipped')
                                               ->count(),
            ],
            'delivery_status' => [
                'pending_deliveries' => Order::where('status', 'processing')
                                           ->with(['items.product'])
                                           ->take(10)
                                           ->get()
                                           ->map(function ($order) {
                                               return [
                                                   'order_id' => $order->id,
                                                   'customer' => $order->customer_name ?? 'Unknown',
                                                   'delivery_address' => $order->delivery_address ?? 'Not specified',
                                                   'items_count' => $order->items->count(),
                                                   'total_weight' => $order->items->sum('weight') ?? 0,
                                               ];
                                           }),
                'today_deliveries' => Order::whereDate('updated_at', $today)
                                         ->where('status', 'shipped')
                                         ->count(),
            ],
            'route_optimization' => [
                'delivery_locations' => Order::where('status', 'processing')
                                           ->select('delivery_address')
                                           ->distinct()
                                           ->count(),
            ],
        ];
    }

    /**
     * Generate retail manager daily report
     */
    private function generateRetailManagerDailyReport(Carbon $today, Carbon $yesterday): array
    {
        return [
            'report_type' => 'retail_manager_daily',
            'date' => $today->format('Y-m-d'),
            'summary' => [
                'orders_received' => Order::whereDate('created_at', $today)->count(),
                'total_sales' => Order::whereDate('created_at', $today)->sum('total_amount'),
                'average_order_value' => Order::whereDate('created_at', $today)->avg('total_amount'),
            ],
            'sales_analysis' => [
                'top_products' => DB::table('order_items')
                                   ->join('products', 'order_items.product_id', '=', 'products.id')
                                   ->whereDate('order_items.created_at', $today)
                                   ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
                                   ->groupBy('products.id', 'products.name')
                                   ->orderBy('total_sold', 'desc')
                                   ->take(5)
                                   ->get(),
                'sales_by_hour' => Order::whereDate('created_at', $today)
                                      ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
                                      ->groupBy('hour')
                                      ->orderBy('hour')
                                      ->get(),
            ],
            'inventory_status' => [
                'low_stock_items' => Inventory::where('quantity', '<=', 10)->count(),
                'out_of_stock_items' => Inventory::where('quantity', 0)->count(),
                'items_needing_restock' => Inventory::where('quantity', '<=', 5)
                                                   ->with(['product'])
                                                   ->get()
                                                   ->map(function ($item) {
                                                       return [
                                                           'product_name' => $item->product->name ?? 'Unknown',
                                                           'current_quantity' => $item->quantity,
                                                           'reorder_level' => $item->reorder_level ?? 10,
                                                       ];
                                                   }),
            ],
        ];
    }

    /**
     * Generate customer daily report
     */
    private function generateCustomerDailyReport(Carbon $today, Carbon $yesterday): array
    {
        return [
            'report_type' => 'customer_daily',
            'date' => $today->format('Y-m-d'),
            'summary' => [
                'orders_placed' => Order::whereDate('created_at', $today)->count(),
                'total_spent' => Order::whereDate('created_at', $today)->sum('total_amount'),
            ],
            'order_status' => [
                'pending_orders' => Order::where('status', 'pending')->count(),
                'processing_orders' => Order::where('status', 'processing')->count(),
                'shipped_orders' => Order::where('status', 'shipped')->count(),
            ],
            'recent_orders' => Order::whereDate('created_at', $today)
                                   ->with(['items.product'])
                                   ->take(5)
                                   ->get()
                                   ->map(function ($order) {
                                       return [
                                           'order_id' => $order->id,
                                           'total_amount' => $order->total_amount,
                                           'status' => $order->status,
                                           'items' => $order->items->map(function ($item) {
                                               return [
                                                   'product_name' => $item->product->name ?? 'Unknown',
                                                   'quantity' => $item->quantity,
                                                   'price' => $item->price,
                                               ];
                                           }),
                                       ];
                                   }),
        ];
    }

    // Weekly report methods follow similar pattern but with weekly data
    private function generateAdminWeeklyReport(Carbon $weekStart, Carbon $weekEnd, Carbon $lastWeekStart, Carbon $lastWeekEnd): array
    {
        return [
            'report_type' => 'admin_weekly',
            'period' => $weekStart->format('Y-m-d') . ' to ' . $weekEnd->format('Y-m-d'),
            'summary' => [
                'total_orders' => Order::whereBetween('created_at', [$weekStart, $weekEnd])->count(),
                'total_revenue' => Order::whereBetween('created_at', [$weekStart, $weekEnd])->sum('total_amount'),
                'avg_order_value' => Order::whereBetween('created_at', [$weekStart, $weekEnd])->avg('total_amount'),
                'growth_rate' => $this->calculateGrowthRate('orders', $weekStart, $weekEnd, $lastWeekStart, $lastWeekEnd),
            ],
            'trends' => [
                'daily_orders' => $this->getDailyTrends('orders', $weekStart, $weekEnd),
                'daily_revenue' => $this->getDailyTrends('revenue', $weekStart, $weekEnd),
            ],
        ];
    }

    private function generateSupplierWeeklyReport(Carbon $weekStart, Carbon $weekEnd, Carbon $lastWeekStart, Carbon $lastWeekEnd): array
    {
        return [
            'report_type' => 'supplier_weekly',
            'period' => $weekStart->format('Y-m-d') . ' to ' . $weekEnd->format('Y-m-d'),
            'summary' => [
                'orders_fulfilled' => Order::whereBetween('created_at', [$weekStart, $weekEnd])
                                         ->whereIn('status', ['completed', 'shipped'])
                                         ->count(),
                'total_order_value' => Order::whereBetween('created_at', [$weekStart, $weekEnd])
                                          ->whereIn('status', ['completed', 'shipped'])
                                          ->sum('total_amount'),
                'inventory_turnover' => $this->calculateInventoryTurnover($weekStart, $weekEnd),
            ],
        ];
    }

    private function generateBakeryManagerWeeklyReport(Carbon $weekStart, Carbon $weekEnd, Carbon $lastWeekStart, Carbon $lastWeekEnd): array
    {
        return [
            'report_type' => 'bakery_manager_weekly',
            'period' => $weekStart->format('Y-m-d') . ' to ' . $weekEnd->format('Y-m-d'),
            'summary' => [
                'batches_completed' => ProductionBatch::whereBetween('actual_end', [$weekStart, $weekEnd])
                                                    ->where('status', 'Completed')
                                                    ->count(),
                'production_efficiency' => $this->calculateProductionEfficiency($weekStart, $weekEnd),
                'ingredient_consumption' => $this->calculateIngredientConsumption($weekStart, $weekEnd),
            ],
        ];
    }

    private function generateDistributorWeeklyReport(Carbon $weekStart, Carbon $weekEnd, Carbon $lastWeekStart, Carbon $lastWeekEnd): array
    {
        return [
            'report_type' => 'distributor_weekly',
            'period' => $weekStart->format('Y-m-d') . ' to ' . $weekEnd->format('Y-m-d'),
            'summary' => [
                'deliveries_completed' => Order::whereBetween('updated_at', [$weekStart, $weekEnd])
                                            ->where('status', 'shipped')
                                            ->count(),
                'delivery_efficiency' => $this->calculateDeliveryEfficiency($weekStart, $weekEnd),
                'route_optimization' => $this->calculateRouteOptimization($weekStart, $weekEnd),
            ],
        ];
    }

    private function generateRetailManagerWeeklyReport(Carbon $weekStart, Carbon $weekEnd, Carbon $lastWeekStart, Carbon $lastWeekEnd): array
    {
        return [
            'report_type' => 'retail_manager_weekly',
            'period' => $weekStart->format('Y-m-d') . ' to ' . $weekEnd->format('Y-m-d'),
            'summary' => [
                'total_sales' => Order::whereBetween('created_at', [$weekStart, $weekEnd])->sum('total_amount'),
                'total_orders' => Order::whereBetween('created_at', [$weekStart, $weekEnd])->count(),
                'avg_order_value' => Order::whereBetween('created_at', [$weekStart, $weekEnd])->avg('total_amount'),
                'growth_rate' => $this->calculateGrowthRate('revenue', $weekStart, $weekEnd, $lastWeekStart, $lastWeekEnd),
            ],
            'top_products' => $this->getTopProducts($weekStart, $weekEnd),
        ];
    }

    private function generateCustomerWeeklyReport(Carbon $weekStart, Carbon $weekEnd, Carbon $lastWeekStart, Carbon $lastWeekEnd): array
    {
        return [
            'report_type' => 'customer_weekly',
            'period' => $weekStart->format('Y-m-d') . ' to ' . $weekEnd->format('Y-m-d'),
            'summary' => [
                'orders_placed' => Order::whereBetween('created_at', [$weekStart, $weekEnd])->count(),
                'total_spent' => Order::whereBetween('created_at', [$weekStart, $weekEnd])->sum('total_amount'),
                'avg_order_value' => Order::whereBetween('created_at', [$weekStart, $weekEnd])->avg('total_amount'),
            ],
        ];
    }

    // Helper methods for calculations
    private function calculateGrowthRate(string $metric, Carbon $currentStart, Carbon $currentEnd, Carbon $previousStart, Carbon $previousEnd): float
    {
        $currentValue = $this->getMetricValue($metric, $currentStart, $currentEnd);
        $previousValue = $this->getMetricValue($metric, $previousStart, $previousEnd);
        
        if ($previousValue == 0) return 0;
        
        return (($currentValue - $previousValue) / $previousValue) * 100;
    }

    private function getMetricValue(string $metric, Carbon $start, Carbon $end): float
    {
        switch ($metric) {
            case 'orders':
                return Order::whereBetween('created_at', [$start, $end])->count();
            case 'revenue':
                return Order::whereBetween('created_at', [$start, $end])->sum('total_amount');
            default:
                return 0;
        }
    }

    private function getDailyTrends(string $metric, Carbon $start, Carbon $end): array
    {
        $trends = [];
        $current = $start->copy();
        
        while ($current <= $end) {
            $trends[] = [
                'date' => $current->format('Y-m-d'),
                'value' => $this->getMetricValue($metric, $current, $current),
            ];
            $current->addDay();
        }
        
        return $trends;
    }

    private function calculateInventoryTurnover(Carbon $start, Carbon $end): float
    {
        // Simplified inventory turnover calculation
        $avgInventory = Inventory::avg('quantity');
        $totalSold = DB::table('order_items')
                      ->whereBetween('created_at', [$start, $end])
                      ->sum('quantity');
        
        return $avgInventory > 0 ? $totalSold / $avgInventory : 0;
    }

    private function calculateProductionEfficiency(Carbon $start, Carbon $end): float
    {
        $totalBatches = ProductionBatch::whereBetween('scheduled_start', [$start, $end])->count();
        $completedBatches = ProductionBatch::whereBetween('actual_end', [$start, $end])
                                         ->where('status', 'Completed')
                                         ->count();
        
        return $totalBatches > 0 ? ($completedBatches / $totalBatches) * 100 : 0;
    }

    private function calculateIngredientConsumption(Carbon $start, Carbon $end): array
    {
        return DB::table('inventory_movements')
                ->join('inventory', 'inventory_movements.inventory_id', '=', 'inventory.id')
                ->join('products', 'inventory.product_id', '=', 'products.id')
                ->whereBetween('inventory_movements.created_at', [$start, $end])
                ->where('inventory_movements.type', 'out')
                ->select('products.name', DB::raw('SUM(inventory_movements.quantity) as total_consumed'))
                ->groupBy('products.id', 'products.name')
                ->get()
                ->toArray();
    }

    private function calculateDeliveryEfficiency(Carbon $start, Carbon $end): float
    {
        $totalDeliveries = Order::whereBetween('created_at', [$start, $end])
                               ->whereIn('status', ['shipped', 'completed'])
                               ->count();
        $onTimeDeliveries = Order::whereBetween('created_at', [$start, $end])
                                ->whereIn('status', ['shipped', 'completed'])
                                ->where('delivery_date', '>=', DB::raw('actual_delivery_date'))
                                ->count();
        
        return $totalDeliveries > 0 ? ($onTimeDeliveries / $totalDeliveries) * 100 : 0;
    }

    private function calculateRouteOptimization(Carbon $start, Carbon $end): array
    {
        return [
            'total_deliveries' => Order::whereBetween('created_at', [$start, $end])
                                     ->where('status', 'shipped')
                                     ->count(),
            'unique_locations' => Order::whereBetween('created_at', [$start, $end])
                                     ->where('status', 'shipped')
                                     ->select('delivery_address')
                                     ->distinct()
                                     ->count(),
        ];
    }

    private function getTopProducts(Carbon $start, Carbon $end): array
    {
        return DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->whereBetween('order_items.created_at', [$start, $end])
                ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'), DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue'))
                ->groupBy('products.id', 'products.name')
                ->orderBy('total_revenue', 'desc')
                ->take(10)
                ->get()
                ->toArray();
    }
} 