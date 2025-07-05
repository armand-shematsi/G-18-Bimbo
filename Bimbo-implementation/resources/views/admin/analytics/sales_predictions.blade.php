@extends('layouts.dashboard')

@section('header')
    Analytics Dashboard
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6 flex space-x-4">
                    <a href="{{ route('admin.analytics') }}" class="px-4 py-2 rounded {{ request()->routeIs('admin.analytics') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">Customer Segmentation</a>
                    <a href="{{ route('admin.analytics.sales_predictions') }}" class="px-4 py-2 rounded {{ request()->routeIs('admin.analytics.sales_predictions') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">Sales Predictions</a>
                </div>
                <h3 class="text-lg font-semibold mb-4">Data Status</h3>
                <div class="mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-gray-50 p-4 rounded">
                            <div class="text-sm text-gray-600">Total Orders</div>
                            <div class="text-xl font-bold">{{ $salesData['total_orders'] }}</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <div class="text-sm text-gray-600">ML Forecasts</div>
                            <div class="text-xl font-bold">{{ count($mlDemandForecast) }}</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <div class="text-sm text-gray-600">Customer Segments</div>
                            <div class="text-xl font-bold">{{ count($segmentRecommendations) }}</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <div class="text-sm text-gray-600">Products</div>
                            <div class="text-xl font-bold">{{ count($topProducts) }}</div>
                        </div>
                    </div>
                </div>

                <h3 class="text-lg font-semibold mb-4">Sales Overview</h3>
                @if($salesData['current_month_sales'] > 0)
                    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded">
                        <p class="text-sm text-blue-700">
                            <strong>Note:</strong> This dashboard shows real sales data from your database.
                        </p>
                    </div>
                @else
                    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded">
                        <p class="text-sm text-yellow-700">
                            <strong>Note:</strong> Sample data is being displayed for demonstration purposes.
                            Create real orders to see actual analytics.
                        </p>
                    </div>
                @endif
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-blue-100 p-4 rounded">
                        <div class="text-sm text-gray-600">Current Month Sales</div>
                        <div class="text-2xl font-bold">₦{{ number_format($salesData['current_month_sales'], 2) }}</div>
                    </div>
                    <div class="bg-green-100 p-4 rounded">
                        <div class="text-sm text-gray-600">Last Month Sales</div>
                        <div class="text-2xl font-bold">₦{{ number_format($salesData['last_month_sales'], 2) }}</div>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded">
                        <div class="text-sm text-gray-600">Growth Rate</div>
                        <div class="text-2xl font-bold">{{ $salesData['growth_rate'] }}%</div>
                    </div>
                    <div class="bg-purple-100 p-4 rounded">
                        <div class="text-sm text-gray-600">Total Orders</div>
                        <div class="text-2xl font-bold">{{ $salesData['total_orders'] }}</div>
                    </div>
                    <div class="bg-pink-100 p-4 rounded">
                        <div class="text-sm text-gray-600">Avg Order Value</div>
                        <div class="text-2xl font-bold">₦{{ number_format($salesData['average_order_value'], 2) }}</div>
                    </div>
                </div>
                <h3 class="text-lg font-semibold mb-4">Sales Trends (Last 12 Months)</h3>
                <div class="mb-8">
                    <canvas id="salesTrendsChart" width="600" height="250"></canvas>
                </div>
                <h3 class="text-lg font-semibold mb-4">Demand Forecast (Next 30 Days)</h3>
                <div class="mb-8">
                    <canvas id="demandForecastChart" width="600" height="250"></canvas>
                </div>

                <h3 class="text-lg font-semibold mb-4">ML-Generated Product Demand Forecast</h3>
                <div class="mb-8">
                    <table class="min-w-full bg-white border border-gray-200 mb-4">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border-b">Date</th>
                                <th class="px-4 py-2 border-b">Product Type</th>
                                <th class="px-4 py-2 border-b">Predicted Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(array_slice($mlDemandForecast, 0, 20) as $forecast)
                                <tr>
                                    <td class="px-4 py-2 border-b">{{ $forecast['date'] }}</td>
                                    <td class="px-4 py-2 border-b">{{ $forecast['product_type'] }}</td>
                                    <td class="px-4 py-2 border-b">{{ $forecast['predicted_quantity'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if(count($mlDemandForecast) > 20)
                        <p class="text-sm text-gray-600">Showing first 20 predictions. Total: {{ count($mlDemandForecast) }} predictions</p>
                    @endif
                </div>

                <h3 class="text-lg font-semibold mb-4">Product Demand Summary (ML)</h3>
                <div class="mb-8">
                    @php
                        $productSummary = [];
                        foreach($mlDemandForecast as $forecast) {
                            if (!isset($productSummary[$forecast['product_type']])) {
                                $productSummary[$forecast['product_type']] = 0;
                            }
                            $productSummary[$forecast['product_type']] += $forecast['predicted_quantity'];
                        }
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($productSummary as $product => $total)
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-green-800">{{ $product }}</h4>
                                <p class="text-2xl font-bold text-green-600">{{ number_format($total) }}</p>
                                <p class="text-sm text-green-600">Total predicted demand (30 days)</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <h3 class="text-lg font-semibold mb-4">Top Products</h3>
                <div class="mb-8">
                    <table class="min-w-full bg-white border border-gray-200 mb-4">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border-b">Product</th>
                                <th class="px-4 py-2 border-b">Total Sold</th>
                                <th class="px-4 py-2 border-b">Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $product)
                                <tr>
                                    <td class="px-4 py-2 border-b">{{ $product->name }}</td>
                                    <td class="px-4 py-2 border-b">{{ $product->total_sold }}</td>
                                    <td class="px-4 py-2 border-b">₦{{ number_format($product->total_revenue, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <h3 class="text-lg font-semibold mb-4">Inventory Predictions</h3>
                <div class="mb-8">
                    <table class="min-w-full bg-white border border-gray-200 mb-4">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border-b">Product</th>
                                <th class="px-4 py-2 border-b">Current Stock</th>
                                <th class="px-4 py-2 border-b">Reorder Level</th>
                                <th class="px-4 py-2 border-b">Daily Usage</th>
                                <th class="px-4 py-2 border-b">Days Until Stockout</th>
                                <th class="px-4 py-2 border-b">Recommended Order Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventoryPredictions as $item)
                                <tr>
                                    <td class="px-4 py-2 border-b">{{ $item['product_name'] }}</td>
                                    <td class="px-4 py-2 border-b">{{ $item['current_stock'] }}</td>
                                    <td class="px-4 py-2 border-b">{{ $item['reorder_level'] }}</td>
                                    <td class="px-4 py-2 border-b">{{ $item['daily_usage'] }}</td>
                                    <td class="px-4 py-2 border-b">{{ $item['days_until_stockout'] }}</td>
                                    <td class="px-4 py-2 border-b">{{ $item['recommended_order_quantity'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Debug: Log data to console
    console.log('Sales Trends Data:', @json($salesTrends));
    console.log('Demand Forecast Data:', @json($demandForecast));

    // Sales Trends Chart
    const salesTrends = @json($salesTrends);
    const salesTrendsLabels = salesTrends.map(t => t.month);
    const salesTrendsData = salesTrends.map(t => t.sales);

    if (salesTrendsData.length > 0) {
        new Chart(document.getElementById('salesTrendsChart'), {
            type: 'line',
            data: {
                labels: salesTrendsLabels,
                datasets: [{
                    label: 'Sales',
                    data: salesTrendsData,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.1)',
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₦' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    } else {
        document.getElementById('salesTrendsChart').innerHTML = '<div class="text-center text-gray-500 py-8">No sales data available</div>';
    }

    // Demand Forecast Chart
    const demandForecast = @json($demandForecast);
    const forecastLabels = demandForecast.map(f => f.date);
    const forecastData = demandForecast.map(f => f.predicted_sales);
    const forecastLower = demandForecast.map(f => f.confidence_lower);
    const forecastUpper = demandForecast.map(f => f.confidence_upper);

    if (forecastData.length > 0) {
        new Chart(document.getElementById('demandForecastChart'), {
            type: 'line',
            data: {
                labels: forecastLabels,
                datasets: [
                    {
                        label: 'Predicted Sales',
                        data: forecastData,
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5,150,105,0.1)',
                        fill: true,
                        tension: 0.4,
                    },
                    {
                        label: 'Lower Bound',
                        data: forecastLower,
                        borderColor: '#f59e42',
                        borderDash: [5,5],
                        fill: false,
                        tension: 0.4,
                    },
                    {
                        label: 'Upper Bound',
                        data: forecastUpper,
                        borderColor: '#f59e42',
                        borderDash: [5,5],
                        fill: false,
                        tension: 0.4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₦' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    } else {
        document.getElementById('demandForecastChart').innerHTML = '<div class="text-center text-gray-500 py-8">No forecast data available</div>';
    }
</script>
@endpush
