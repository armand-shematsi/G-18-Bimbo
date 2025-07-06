<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Inventory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Sales Analytics
        $salesData = $this->getSalesAnalytics();
        $demandForecast = $this->getDemandForecast();
        $customerSegments = $this->getCustomerSegments();
        $topProducts = $this->getTopProducts();
        $salesTrends = $this->getSalesTrends();
        $inventoryPredictions = $this->getInventoryPredictions();

        // Customer Segmentation Analytics (ML-based)
        $segmentRecommendations = $this->getSegmentRecommendations();
        $breadTypeDistribution = $this->getBreadTypeDistribution();
        $locationDistribution = $this->getLocationDistribution();
        $avgPurchaseFrequency = $this->getAvgPurchaseFrequency();

        return view('admin.analytics.index', compact(
            'salesData',
            'demandForecast',
            'customerSegments',
            'topProducts',
            'salesTrends',
            'inventoryPredictions',
            'segmentRecommendations',
            'breadTypeDistribution',
            'locationDistribution',
            'avgPurchaseFrequency'
        ));
    }

    private function getSalesAnalytics()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $currentMonthSales = Order::where('created_at', '>=', $currentMonth)
            ->where('status', 'completed')
            ->sum('total_amount');

        $lastMonthSales = Order::where('created_at', '>=', $lastMonth)
            ->where('created_at', '<', $currentMonth)
            ->where('status', 'completed')
            ->sum('total_amount');

        // If no sales data, use sample data for demonstration
        if ($currentMonthSales == 0 && $lastMonthSales == 0) {
            $currentMonthSales = 250000; // Sample current month sales
            $lastMonthSales = 220000;    // Sample last month sales
        }

        $growthRate = $lastMonthSales > 0 ? (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100 : 0;

        $totalOrders = Order::where('status', 'completed')->count();
        $averageOrderValue = Order::where('status', 'completed')->avg('total_amount') ?? 0;

        // If no orders, use sample data
        if ($totalOrders == 0) {
            $totalOrders = 15;
            $averageOrderValue = 30000;
        }

        return [
            'current_month_sales' => $currentMonthSales,
            'last_month_sales' => $lastMonthSales,
            'growth_rate' => round($growthRate, 2),
            'total_orders' => $totalOrders,
            'average_order_value' => round($averageOrderValue, 2),
        ];
    }

    private function getDemandForecast()
    {
        // Get last 30 days of sales data
        $historicalSales = Order::where('created_at', '>=', Carbon::now()->subDays(30))
            ->where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as daily_sales')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $forecastData = [];

        // If no historical data, create sample forecast
        if ($historicalSales->count() == 0) {
            $baseSales = 8000; // Base daily sales
            $trend = 150; // Daily growth trend

            for ($i = 1; $i <= 30; $i++) {
                $forecastDate = Carbon::now()->addDays($i);
                $predictedSales = $baseSales + ($trend * $i) + (rand(-500, 500)); // Add some randomness

                $forecastData[] = [
                    'date' => $forecastDate->format('Y-m-d'),
                    'predicted_sales' => max(0, round($predictedSales, 2)),
                    'confidence_lower' => max(0, round($predictedSales * 0.8, 2)),
                    'confidence_upper' => round($predictedSales * 1.2, 2),
                ];
            }
        } else {
            $avgDailySales = $historicalSales->avg('daily_sales');
            $trend = $this->calculateTrend($historicalSales);

            // Generate 30-day forecast
            for ($i = 1; $i <= 30; $i++) {
                $forecastDate = Carbon::now()->addDays($i);
                $predictedSales = $avgDailySales + ($trend * $i);

                $forecastData[] = [
                    'date' => $forecastDate->format('Y-m-d'),
                    'predicted_sales' => max(0, round($predictedSales, 2)),
                    'confidence_lower' => max(0, round($predictedSales * 0.8, 2)),
                    'confidence_upper' => round($predictedSales * 1.2, 2),
                ];
            }
        }

        return $forecastData;
    }

    private function calculateTrend($historicalData)
    {
        if ($historicalData->count() < 2) return 0;

        $n = $historicalData->count();
        $sumX = 0;
        $sumY = 0;
        $sumXY = 0;
        $sumX2 = 0;

        foreach ($historicalData as $index => $data) {
            $x = $index;
            $y = $data->daily_sales;

            $sumX += $x;
            $sumY += $y;
            $sumXY += $x * $y;
            $sumX2 += $x * $x;
        }

        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        return $slope;
    }

    private function getCustomerSegments()
    {
        // Analyze customer behavior for segmentation
        $customers = User::where('role', 'customer')
            ->withCount(['orders' => function($query) {
                $query->where('status', 'completed');
            }])
            ->withSum(['orders' => function($query) {
                $query->where('status', 'completed');
            }], 'total_amount')
            ->get();

        $segments = [
            'high_value' => $customers->filter(function($customer) {
                return $customer->orders_sum_total_amount > 1000 && $customer->orders_count > 5;
            })->count(),
            'medium_value' => $customers->filter(function($customer) {
                return $customer->orders_sum_total_amount > 500 && $customer->orders_count > 2;
            })->count(),
            'low_value' => $customers->filter(function($customer) {
                return $customer->orders_sum_total_amount <= 500 || $customer->orders_count <= 2;
            })->count(),
        ];

        return $segments;
    }

    private function getTopProducts()
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->selectRaw('products.name, SUM(order_items.quantity) as total_sold, SUM(order_items.quantity * order_items.unit_price) as total_revenue')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();
    }

    private function getSalesTrends()
    {
        $trends = [];

        // Last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $sales = Order::where('created_at', '>=', $month->startOfMonth())
                ->where('created_at', '<', $month->copy()->addMonth()->startOfMonth())
                ->where('status', 'completed')
                ->sum('total_amount');

            $orders = Order::where('created_at', '>=', $month->startOfMonth())
                ->where('created_at', '<', $month->copy()->addMonth()->startOfMonth())
                ->where('status', 'completed')
                ->count();

            $trends[] = [
                'month' => $month->format('M Y'),
                'sales' => $sales,
                'orders' => $orders,
            ];
        }

        // If no sales data, create sample trends
        if (array_sum(array_column($trends, 'sales')) == 0) {
            $baseSales = 200000;
            for ($i = 0; $i < count($trends); $i++) {
                $trends[$i]['sales'] = $baseSales + (rand(-30000, 50000)) + ($i * 10000);
                $trends[$i]['orders'] = rand(8, 25);
            }
        }

        return $trends;
    }

    private function getInventoryPredictions()
    {
        $predictions = [];

        // Get low stock items that need reordering
        $lowStockItems = Inventory::where('quantity', '<=', DB::raw('reorder_level'))
            ->with('product')
            ->get();

        foreach ($lowStockItems as $item) {
            // Skip if product is null
            if (!$item->product) {
                continue;
            }

            // Simple prediction: if current trend continues, when will we run out?
            $dailyUsage = $this->calculateDailyUsage($item->product_id);
            $daysUntilStockout = $dailyUsage > 0 ? floor($item->quantity / $dailyUsage) : 0;

            $predictions[] = [
                'product_name' => $item->product->name,
                'current_stock' => $item->quantity,
                'reorder_level' => $item->reorder_level,
                'daily_usage' => round($dailyUsage, 2),
                'days_until_stockout' => $daysUntilStockout,
                'recommended_order_quantity' => max(50, $dailyUsage * 30), // 30 days supply
            ];
        }

        return $predictions;
    }

    private function calculateDailyUsage($productId)
    {
        // Calculate average daily usage based on last 30 days
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        $totalSold = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('order_items.product_id', $productId)
            ->where('orders.created_at', '>=', $thirtyDaysAgo)
            ->where('orders.status', 'completed')
            ->sum('order_items.quantity');

        return $totalSold / 30;
    }

    public function exportForecast()
    {
        $forecast = $this->getDemandForecast();

        $filename = 'sales_forecast_' . Carbon::now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($forecast) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Predicted Sales', 'Confidence Lower', 'Confidence Upper']);

            foreach ($forecast as $row) {
                fputcsv($file, [
                    $row['date'],
                    $row['predicted_sales'],
                    $row['confidence_lower'],
                    $row['confidence_upper']
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function salesPredictions()
    {
        // Sales Analytics
        $salesData = $this->getSalesAnalytics();
        $demandForecast = $this->getDemandForecast();
        $mlDemandForecast = $this->getMLDemandForecast();
        $customerSegments = $this->getCustomerSegments();
        $topProducts = $this->getTopProducts();
        $salesTrends = $this->getSalesTrends();
        $inventoryPredictions = $this->getInventoryPredictions();

        // Customer Segmentation Analytics (ML-based)
        $segmentRecommendations = $this->getSegmentRecommendations();
        $breadTypeDistribution = $this->getBreadTypeDistribution();
        $locationDistribution = $this->getLocationDistribution();
        $avgPurchaseFrequency = $this->getAvgPurchaseFrequency();

        return view('admin.analytics.sales_predictions', compact(
            'salesData',
            'demandForecast',
            'mlDemandForecast',
            'customerSegments',
            'topProducts',
            'salesTrends',
            'inventoryPredictions',
            'segmentRecommendations',
            'breadTypeDistribution',
            'locationDistribution',
            'avgPurchaseFrequency'
        ));
    }

    private function getMLDemandForecast()
    {
        $csvPath = base_path('ml/product_demand_forecast.csv');

        if (!File::exists($csvPath)) {
            return [];
        }

        $forecastData = [];
        $handle = fopen($csvPath, 'r');

        // Skip header
        fgetcsv($handle);

        while (($data = fgetcsv($handle)) !== false) {
            $forecastData[] = [
                'date' => $data[0],
                'product_type' => $data[1],
                'predicted_quantity' => (int)$data[2]
            ];
        }

        fclose($handle);

        return $forecastData;
    }

    private function getSegmentRecommendations()
    {
        $csvPath = base_path('ml/customer_segments_with_recommendations.csv');

        if (!File::exists($csvPath)) {
            return [];
        }

        $segments = [];
        $handle = fopen($csvPath, 'r');

        // Skip header
        fgetcsv($handle);

        while (($data = fgetcsv($handle)) !== false) {
            $segments[] = [
                'name' => $data[0],
                'purchase_frequency' => (float)$data[1],
                'avg_spending' => (float)$data[2],
                'preferred_bread_type' => $data[3],
                'location' => $data[4],
                'segment' => (int)$data[5]
            ];
        }

        fclose($handle);

        return $segments;
    }

    private function getBreadTypeDistribution()
    {
        $segments = $this->getSegmentRecommendations();
        $distribution = [];

        foreach ($segments as $segment) {
            $breadType = $segment['preferred_bread_type'];
            if (!isset($distribution[$breadType])) {
                $distribution[$breadType] = 0;
            }
            $distribution[$breadType]++;
        }

        return $distribution;
    }

    private function getLocationDistribution()
    {
        $segments = $this->getSegmentRecommendations();
        $distribution = [];

        foreach ($segments as $segment) {
            $location = $segment['location'];
            if (!isset($distribution[$location])) {
                $distribution[$location] = 0;
            }
            $distribution[$location]++;
        }

        return $distribution;
    }

    private function getAvgPurchaseFrequency()
    {
        $segments = $this->getSegmentRecommendations();
        $frequencyBySegment = [];

        foreach ($segments as $segment) {
            $segmentNum = $segment['segment'];
            if (!isset($frequencyBySegment[$segmentNum])) {
                $frequencyBySegment[$segmentNum] = ['total' => 0, 'count' => 0];
            }
            $frequencyBySegment[$segmentNum]['total'] += $segment['purchase_frequency'];
            $frequencyBySegment[$segmentNum]['count']++;
        }

        $avgFrequency = [];
        foreach ($frequencyBySegment as $segmentNum => $data) {
            $avgFrequency[$segmentNum] = $data['total'] / $data['count'];
        }

        return $avgFrequency;
    }
}
