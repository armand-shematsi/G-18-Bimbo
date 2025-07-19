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
use Illuminate\Support\Facades\Http;

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
        $detailedSegments = $this->getDetailedSegments();

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
            'avgPurchaseFrequency',
            'detailedSegments'
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
        // Prioritize ML-based forecast
        $mlForecast = $this->getMLDemandForecast();

        if (!empty($mlForecast)) {
            // Use ML data to create enhanced forecast
            $forecastData = [];
            $productForecasts = [];

            // Group ML predictions by product
            foreach ($mlForecast as $forecast) {
                $product = $forecast['product_type'];
                if (!isset($productForecasts[$product])) {
                    $productForecasts[$product] = [];
                }
                $productForecasts[$product][] = $forecast['predicted_quantity'];
            }

            // Create 30-day forecast based on ML predictions
            for ($i = 1; $i <= 30; $i++) {
                $forecastDate = Carbon::now()->addDays($i);
                $totalPredicted = 0;

                // Sum predictions across all products for each day
                foreach ($productForecasts as $product => $predictions) {
                    $dayIndex = ($i - 1) % count($predictions);
                    $totalPredicted += $predictions[$dayIndex] ?? 0;
                }

                // Convert quantity to sales value (assuming average price)
                $avgPrice = 500; // Average price per unit
                $predictedSales = $totalPredicted * $avgPrice;

                $forecastData[] = [
                    'date' => $forecastDate->format('Y-m-d'),
                    'predicted_sales' => $predictedSales,
                    'confidence_lower' => $predictedSales * 0.85,
                    'confidence_upper' => $predictedSales * 1.15,
                    'ml_based' => true,
                ];
            }

            return $forecastData;
        }

        // Fallback to statistical forecast if no ML data
        $historicalSales = Order::where('created_at', '>=', Carbon::now()->subDays(30))
            ->where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as daily_sales')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $forecastData = [];

        if ($historicalSales->count() == 0) {
            $baseSales = 8000;
            $trend = 150;

            for ($i = 1; $i <= 30; $i++) {
                $forecastDate = Carbon::now()->addDays($i);
                $predictedSales = $baseSales + ($trend * $i) + (rand(-500, 500));

                $forecastData[] = [
                    'date' => $forecastDate->format('Y-m-d'),
                    'predicted_sales' => max(0, round($predictedSales, 2)),
                    'confidence_lower' => max(0, round($predictedSales * 0.8, 2)),
                    'confidence_upper' => round($predictedSales * 1.2, 2),
                    'ml_based' => false,
                ];
            }
        } else {
            $avgDailySales = $historicalSales->avg('daily_sales');
            $trend = $this->calculateTrend($historicalSales);

            for ($i = 1; $i <= 30; $i++) {
                $forecastDate = Carbon::now()->addDays($i);
                $predictedSales = $avgDailySales + ($trend * $i);

                $forecastData[] = [
                    'date' => $forecastDate->format('Y-m-d'),
                    'predicted_sales' => max(0, round($predictedSales, 2)),
                    'confidence_lower' => max(0, round($predictedSales * 0.8, 2)),
                    'confidence_upper' => round($predictedSales * 1.2, 2),
                    'ml_based' => false,
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

    public function salesPredictions(Request $request)
    {
        try {
            $successMessage = null;

            // Check if user wants to refresh ML data
            if ($request->has('refresh_ml')) {
                $this->refreshMLData();
                $successMessage = 'ML data has been successfully refreshed with latest predictions!';
            }

            // ML-Based Analytics (Primary)
            $mlDemandForecast = $this->getMLDemandForecast();
            $segmentRecommendations = $this->getSegmentRecommendations();
            $mlInsights = $this->getMLInsights();

            // Enhanced demand forecast using ML data
            $demandForecast = $this->getDemandForecast();

            // Supporting Analytics
            $salesData = $this->getSalesAnalytics();
            $customerSegments = $this->getCustomerSegments();
            $topProducts = $this->getTopProducts();
            $salesTrends = $this->getSalesTrends();
            $inventoryPredictions = $this->getInventoryPredictions();
            $breadTypeDistribution = $this->getBreadTypeDistribution();
            $locationDistribution = $this->getLocationDistribution();
            $avgPurchaseFrequency = $this->getAvgPurchaseFrequency();

            return view('admin.analytics.sales_predictions', compact(
                'salesData',
                'demandForecast',
                'mlDemandForecast',
                'mlInsights',
                'customerSegments',
                'topProducts',
                'salesTrends',
                'inventoryPredictions',
                'segmentRecommendations',
                'breadTypeDistribution',
                'locationDistribution',
                'avgPurchaseFrequency',
                'successMessage'
            ));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Sales Predictions Error: ' . $e->getMessage());

            // Return view with error message
            return view('admin.analytics.sales_predictions', [
                'error' => 'An error occurred while loading sales predictions: ' . $e->getMessage(),
                'salesData' => [],
                'demandForecast' => [],
                'mlDemandForecast' => [],
                'mlInsights' => [],
                'customerSegments' => [],
                'topProducts' => [],
                'salesTrends' => [],
                'inventoryPredictions' => [],
                'segmentRecommendations' => [],
                'breadTypeDistribution' => [],
                'locationDistribution' => [],
                'avgPurchaseFrequency' => [],
                'successMessage' => null
            ]);
        }
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
            if (count($data) >= 3) {
                $forecastData[] = [
                    'date' => $data[0],
                    'product_type' => $data[1],
                    'predicted_quantity' => (int)$data[2]
                ];
            }
        }

        fclose($handle);

        return $forecastData;
    }

    private function getSegmentRecommendations()
    {
        // Try to read from the detailed customer segments file first
        $csvPath = base_path('ml/customer_segments_detailed.csv');

        if (!File::exists($csvPath)) {
            // Fallback to summary file
            $csvPath = base_path('ml/customer_segments_summary.csv');
            if (!File::exists($csvPath)) {
                // Fallback to old file
                $csvPath = base_path('ml/customer_segments_with_recommendations.csv');
                if (!File::exists($csvPath)) {
                    return [];
                }
            }
        }

        $customers = [];
        $handle = fopen($csvPath, 'r');

        // Skip header
        fgetcsv($handle);

        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) >= 15) {
                // Detailed format from customer_segments_detailed.csv
                $customers[] = [
                    'name' => $data[0], // Customer ID
                    'segment' => (int)$data[14], // segment column
                    'type' => 'Customer',
                    'customer_count' => 1,
                    'avg_spending' => (float)$data[2], // avg_spending column
                    'purchase_frequency' => (float)$data[1], // purchase_frequency column
                    'preferred_bread_type' => $data[5], // preferred_bread column
                    'location' => $data[6], // location column
                    'total_spending' => (float)$data[3], // total_spending column
                    'customer_lifetime' => (float)$data[4], // customer_lifetime column
                    'payment_method' => $data[7], // payment_method column
                    'satisfaction' => (float)$data[8], // satisfaction column
                    'recommendations' => $this->getCustomerRecommendation((int)$data[14], (float)$data[2], (float)$data[1])
                ];
            } else if (count($data) >= 7) {
                // Summary format from customer_segments_summary.csv
                $customers[] = [
                    'name' => 'Customer Segment ' . $data[0],
                    'segment' => (int)$data[0],
                    'type' => $data[1],
                    'customer_count' => (int)$data[2],
                    'avg_spending' => (float)$data[3],
                    'purchase_frequency' => (float)$data[4],
                    'preferred_bread_type' => $data[5],
                    'location' => $data[6],
                    'recommendations' => $data[7] ?? ''
                ];
            } else {
                // Old format fallback
                $customers[] = [
                    'name' => 'Customer Segment ' . ($data[5] ?? 0),
                    'segment' => (int)($data[5] ?? 0),
                    'type' => 'Legacy',
                    'customer_count' => 1,
                    'avg_spending' => (float)($data[2] ?? 0),
                    'purchase_frequency' => (float)($data[1] ?? 0),
                    'preferred_bread_type' => $data[3] ?? '',
                    'location' => $data[4] ?? '',
                    'recommendations' => ''
                ];
            }
        }

        fclose($handle);

        return $customers;
    }

    private function getCustomerRecommendation($segment, $avgSpending, $purchaseFrequency)
    {
        if ($avgSpending > 20000 && $purchaseFrequency > 10) {
            return "High-value customer: Offer premium products and loyalty rewards";
        } elseif ($avgSpending > 10000 && $purchaseFrequency > 5) {
            return "Medium-value customer: Bundle offers and personalized discounts";
        } else {
            return "Growth customer: Win-back campaigns and introductory offers";
        }
    }

    private function getBreadTypeDistribution()
    {
        $segments = $this->getSegmentRecommendations();
        $distribution = [];

        foreach ($segments as $segment) {
            $breadType = $segment['preferred_bread_type'] ?? $segment['preferred_bread'] ?? 'Unknown';
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
            $location = $segment['location'] ?? 'Unknown';
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
            $segmentNum = $segment['segment'] ?? 0;
            if (!isset($frequencyBySegment[$segmentNum])) {
                $frequencyBySegment[$segmentNum] = ['total' => 0, 'count' => 0];
            }
            $frequencyBySegment[$segmentNum]['total'] += $segment['purchase_frequency'] ?? 0;
            $frequencyBySegment[$segmentNum]['count']++;
        }

        $avgFrequency = [];
        foreach ($frequencyBySegment as $segmentNum => $data) {
            $avgFrequency[$segmentNum] = $data['total'] / $data['count'];
        }

        return $avgFrequency;
    }

    private function getMLInsights()
    {
        $insights = [];

        // Analyze ML customer segments
        $segments = $this->getSegmentRecommendations();
        if (!empty($segments)) {
            $segmentAnalysis = [];
            foreach ($segments as $customer) {
                $segment = $customer['segment'] ?? 0;
                if (!isset($segmentAnalysis[$segment])) {
                    $segmentAnalysis[$segment] = [
                        'count' => 0,
                        'total_spending' => 0,
                        'avg_frequency' => 0,
                        'preferred_products' => [],
                        'locations' => []
                    ];
                }
                $segmentAnalysis[$segment]['count']++;
                $segmentAnalysis[$segment]['total_spending'] += $customer['avg_spending'] ?? 0;
                $segmentAnalysis[$segment]['avg_frequency'] += $customer['purchase_frequency'] ?? 0;
                $segmentAnalysis[$segment]['preferred_products'][] = $customer['preferred_bread_type'] ?? $customer['preferred_bread'] ?? 'Unknown';
                $segmentAnalysis[$segment]['locations'][] = $customer['location'] ?? 'Unknown';
            }

            foreach ($segmentAnalysis as $segment => $data) {
                $avgSpending = $data['total_spending'] / $data['count'];
                $avgFrequency = $data['avg_frequency'] / $data['count'];
                $topProduct = array_count_values($data['preferred_products']);
                arsort($topProduct);
                $topLocation = array_count_values($data['locations']);
                arsort($topLocation);

                $insights[] = [
                    'type' => 'customer_segment',
                    'segment' => $segment,
                    'customer_count' => $data['count'],
                    'avg_spending' => round($avgSpending, 2),
                    'avg_frequency' => round($avgFrequency, 1),
                    'top_product' => array_key_first($topProduct),
                    'top_location' => array_key_first($topLocation),
                    'recommendation' => $this->getSegmentRecommendation($segment, $avgSpending, $avgFrequency)
                ];
            }
        }

        // Analyze ML demand forecast
        $mlForecast = $this->getMLDemandForecast();
        if (!empty($mlForecast)) {
            $productDemand = [];
            foreach ($mlForecast as $forecast) {
                $product = $forecast['product_type'];
                if (!isset($productDemand[$product])) {
                    $productDemand[$product] = 0;
                }
                $productDemand[$product] += $forecast['predicted_quantity'];
            }

            arsort($productDemand);
            $topProduct = array_key_first($productDemand);
            $totalDemand = array_sum($productDemand);

            $insights[] = [
                'type' => 'demand_forecast',
                'top_product' => $topProduct,
                'total_demand' => $totalDemand,
                'top_demand' => $productDemand[$topProduct],
                'recommendation' => "Focus production on {$topProduct} - highest predicted demand of {$productDemand[$topProduct]} units"
            ];
        }

        return $insights;
    }

    private function getSegmentRecommendation($segment, $avgSpending, $avgFrequency)
    {
        if ($avgSpending > 10000 && $avgFrequency > 10) {
            return "High-value segment: Offer premium products and loyalty rewards";
        } elseif ($avgSpending > 5000 && $avgFrequency > 5) {
            return "Medium-value segment: Bundle offers and personalized discounts";
        } else {
            return "Growth segment: Win-back campaigns and introductory offers";
        }
    }

    private function getDetailedSegments()
    {
        $csvPath = base_path('ml/customer_segments_detailed.csv');

        if (!File::exists($csvPath)) {
            return [];
        }

        $customers = [];
        $handle = fopen($csvPath, 'r');

        // Skip header
        fgetcsv($handle);

        $count = 0;
        while (($data = fgetcsv($handle)) !== false && $count < 50) { // Limit to first 50 for performance
            if (count($data) >= 10) {
                $customers[] = [
                    'customer_id' => $data[0],
                    'purchase_frequency' => (float)$data[1],
                    'avg_spending' => (float)$data[2],
                    'total_spending' => (float)$data[3],
                    'customer_lifetime' => (float)$data[4],
                    'preferred_bread' => $data[5],
                    'location' => $data[6],
                    'payment_method' => $data[7],
                    'satisfaction' => (float)$data[8],
                    'segment' => (int)$data[9]
                ];
            }
            $count++;
        }

        fclose($handle);

        return $customers;
    }

    private function refreshMLData()
    {
        try {
            $mlPath = base_path('ml');
            $pythonScript = $mlPath . '/product_demand_forecast.py';

            if (!File::exists($pythonScript)) {
                throw new \Exception('Python script not found: ' . $pythonScript);
            }

            // Change to ML directory and run Python script
            $command = "cd {$mlPath} && python product_demand_forecast.py";
            $output = shell_exec($command . ' 2>&1');

            if ($output === null) {
                throw new \Exception('Failed to execute Python script');
            }

            // Log the output for debugging
            \Log::info('ML Data Refresh Output: ' . $output);

        } catch (\Exception $e) {
            \Log::error('ML Data Refresh Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get real-time prediction from Flask API
     */
    public function getRealTimePrediction(Request $request)
    {
        $product = $request->input('product');
        $date = $request->input('date');

        $response = Http::get('http://localhost:5000/predict', [
            'product' => $product,
            'date' => $date
        ]);

        if ($response->successful()) {
            $prediction = $response->json()['predicted_quantity'];
            return view('admin.analytics.realtime_prediction', compact('prediction', 'product', 'date'));
        } else {
            $error = $response->json('error', 'Prediction not available.');
            return view('admin.analytics.realtime_prediction', compact('error', 'product', 'date'));
        }
    }

    /**
     * Show prediction graph for a product using batch predictions from Flask API
     */
    public function showPredictionGraph(Request $request)
    {
        $product = $request->input('product');
        $startDate = $request->input('start_date', now()->toDateString());
        $days = $request->input('days', 30);
        $predictions = [];
        $error = null;
        $products = [];

        // Fetch available products from the CSV file
        $csvPath = base_path('ml/large_sales_cleaned.csv');
        if (file_exists($csvPath)) {
            if (($handle = fopen($csvPath, 'r')) !== false) {
                $header = fgetcsv($handle);
                $productTypeIndex = array_search('ProductType', $header);
                $productSet = [];
                while (($row = fgetcsv($handle)) !== false) {
                    if (isset($row[$productTypeIndex])) {
                        $productSet[$row[$productTypeIndex]] = true;
                    }
                }
                fclose($handle);
                $products = array_keys($productSet);
                sort($products);
            }
        }

        if ($product) {
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:5000/predict/batch', [
                'product' => $product,
                'start_date' => $startDate,
                'days' => $days
            ]);
            if ($response->successful()) {
                $data = $response->json();
                $predictions = $data['predictions'] ?? [];
            } else {
                $error = $response->json('error', 'Prediction not available.');
            }
        }

        return view('admin.analytics.prediction_graph', compact('product', 'startDate', 'days', 'predictions', 'error', 'products'));
    }

    public function adminInventoryAnalytics()
    {
        $inventory = \App\Models\Inventory::all();
        $avgReorderLevel = $inventory->avg('reorder_level') ?: 50;
        $overstockThreshold = $avgReorderLevel * 3;

        $stats = [
            'total' => $inventory->count(),
            'available' => $inventory->where('status', 'available')->count(),
            'low_stock' => $inventory->where('status', 'low_stock')->count(),
            'out_of_stock' => $inventory->where('status', 'out_of_stock')->count(),
            'over_stock' => $inventory->where('quantity', '>', $overstockThreshold)->count(),
        ];

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
        $salesForecastChartData = $forecastResponse; // Should be an array: product => [ {date, predicted}, ... ]

        // ML predictions for next day (as before)
        $predictions = [];
        try {
            $response = \Illuminate\Support\Facades\Http::post('http://127.0.0.1:5000/predict', [
                'sales' => $salesData
            ]);
            if ($response->successful()) {
                $predictions = $response->json('predictions');
            }
        } catch (\Exception $e) {
            $predictions = [];
        }

        $totalInventoryValue = $inventory->sum(function($item) {
            $quantity = $item->quantity ?? 0;
            $unitPrice = $item->unit_price ?? 0;
            return $quantity * $unitPrice;
        });
        $totalReorderValue = $inventory->sum(function($item) {
            return $item->reorder_level * $item->unit_price;
        });
        $efficientStock = $inventory->filter(function($item) {
            return $item->quantity > $item->reorder_level && $item->quantity <= ($item->reorder_level * 2);
        })->count();
        $stockLevelChartData = $inventory->map(function($item) {
            return [
                'item_name' => $item->item_name,
                'quantity' => $item->quantity,
            ];
        });
        $lowStockItems = $inventory->filter(function($item) {
            return $item->quantity <= $item->reorder_level && $item->quantity > 0;
        });

        return view('admin.analytics.index', [
            'adminInventoryStats' => $stats,
            'adminInventoryPredictions' => $predictions,
            'adminTotalInventoryValue' => $totalInventoryValue,
            'adminTotalReorderValue' => $totalReorderValue,
            'adminEfficientStock' => $efficientStock,
            'adminStockLevelChartData' => $stockLevelChartData,
            'adminLowStockItems' => $lowStockItems,
            'adminOverstockThreshold' => $overstockThreshold,
            'salesHistoryChartData' => $salesHistoryChartData,
            'salesForecastChartData' => $salesForecastChartData,
            'customerSegments' => [],
            'segmentRecommendations' => [],
            'breadTypeDistribution' => [],
            'locationDistribution' => [],
            'avgPurchaseFrequency' => [],
            'detailedSegments' => [],
        ]);
    }
}
