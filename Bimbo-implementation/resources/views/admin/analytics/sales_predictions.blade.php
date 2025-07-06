@extends('layouts.dashboard')

@section('header')
    Analytics Dashboard
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6 flex justify-between items-center">
                    <div class="flex space-x-4">
                        <a href="{{ route('admin.analytics.sales_predictions') }}" class="px-4 py-2 rounded bg-blue-500 text-white">Sales Predictions</a>
                    </div>
                    <div class="flex space-x-2">
                        <form action="{{ route('admin.analytics.sales_predictions') }}" method="GET" class="inline">
                            <input type="hidden" name="refresh_ml" value="1">
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">
                                🔄 Refresh ML Data
                            </button>
                        </form>
                    </div>
                </div>

                @if(isset($error))
                    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <h4 class="font-semibold mb-2">Error:</h4>
                        <p>{{ $error }}</p>
                    </div>
                @endif

                @if(isset($successMessage))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        <h4 class="font-semibold mb-2">Success:</h4>
                        <p>{{ $successMessage }}</p>
                    </div>
                @endif

                <!-- ML Data Status -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">🤖 ML Data Status</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <div class="text-sm text-blue-600">ML Forecasts</div>
                            <div class="text-2xl font-bold text-blue-800">{{ count($mlDemandForecast ?? []) }}</div>
                            <div class="text-xs text-blue-600">30-day predictions</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                            <div class="text-sm text-green-600">Products</div>
                            <div class="text-2xl font-bold text-green-800">{{ count(array_unique(array_column($mlDemandForecast ?? [], 'product_type'))) }}</div>
                            <div class="text-xs text-green-600">Product types</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                            <div class="text-sm text-purple-600">Total Demand</div>
                            <div class="text-2xl font-bold text-purple-800">{{ number_format(array_sum(array_column($mlDemandForecast ?? [], 'predicted_quantity'))) }}</div>
                            <div class="text-xs text-purple-600">Units predicted</div>
                        </div>
                        <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                            <div class="text-sm text-orange-600">Data Quality</div>
                            <div class="text-2xl font-bold text-orange-800">Cleaned</div>
                            <div class="text-xs text-orange-600">Using cleaned dataset</div>
                        </div>
                    </div>
                </div>

                <!-- Product Demand Forecast Chart -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">📈 Product Demand Forecast (Next 30 Days)</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <canvas id="productDemandChart" width="800" height="400"></canvas>
                    </div>
                </div>

                <!-- Product Comparison Chart -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">📊 Product Demand Comparison</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <canvas id="productComparisonChart" width="400" height="300"></canvas>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <canvas id="productPieChart" width="400" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Daily Demand Trend -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">📅 Daily Demand Trend</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <canvas id="dailyTrendChart" width="800" height="300"></canvas>
                    </div>
                </div>

                <!-- Product Summary Cards -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">🏷️ Product Demand Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @php
                            $productSummary = [];
                            foreach($mlDemandForecast ?? [] as $forecast) {
                                $productType = $forecast['product_type'] ?? 'Unknown';
                                if (!isset($productSummary[$productType])) {
                                    $productSummary[$productType] = [
                                        'total' => 0,
                                        'avg' => 0,
                                        'max' => 0,
                                        'min' => 999999
                                    ];
                                }
                                $quantity = $forecast['predicted_quantity'] ?? 0;
                                $productSummary[$productType]['total'] += $quantity;
                                $productSummary[$productType]['max'] = max($productSummary[$productType]['max'], $quantity);
                                $productSummary[$productType]['min'] = min($productSummary[$productType]['min'], $quantity);
                            }
                            foreach($productSummary as $product => $data) {
                                $productSummary[$product]['avg'] = round($data['total'] / 30, 1);
                            }
                        @endphp
                        @foreach($productSummary as $product => $data)
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg border border-blue-200">
                                <h4 class="font-bold text-blue-800 text-lg mb-2">{{ $product }}</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-blue-600">Total (30 days):</span>
                                        <span class="font-bold text-blue-800">{{ number_format($data['total']) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-600">Daily Average:</span>
                                        <span class="font-bold text-blue-800">{{ $data['avg'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-600">Peak Demand:</span>
                                        <span class="font-bold text-blue-800">{{ $data['max'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-600">Min Demand:</span>
                                        <span class="font-bold text-blue-800">{{ $data['min'] }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Detailed Forecast Table -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">📋 Detailed Forecast Data</h3>
                    <div class="bg-gray-50 p-4 rounded-lg overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 border-b text-left">Date</th>
                                    <th class="px-4 py-2 border-b text-left">Product Type</th>
                                    <th class="px-4 py-2 border-b text-right">Predicted Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(array_slice($mlDemandForecast ?? [], 0, 30) as $forecast)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 border-b">{{ $forecast['date'] ?? 'N/A' }}</td>
                                        <td class="px-4 py-2 border-b">{{ $forecast['product_type'] ?? 'Unknown' }}</td>
                                        <td class="px-4 py-2 border-b text-right font-semibold">{{ number_format($forecast['predicted_quantity'] ?? 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if(count($mlDemandForecast ?? []) > 30)
                            <p class="text-sm text-gray-600 mt-2">Showing first 30 predictions. Total: {{ count($mlDemandForecast ?? []) }} predictions</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ML Demand Forecast Data
    const mlForecast = @json($mlDemandForecast ?? []);

    if (mlForecast.length > 0) {
        // Process data for charts
        const dates = [...new Set(mlForecast.map(f => f.date))].sort();
        const products = [...new Set(mlForecast.map(f => f.product_type))];

        // Product Demand Forecast Chart (Line Chart)
        const productDemandCtx = document.getElementById('productDemandChart').getContext('2d');
        const datasets = products.map((product, index) => {
            const colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'];
            const data = dates.map(date => {
                const forecast = mlForecast.find(f => f.date === date && f.product_type === product);
                return forecast ? forecast.predicted_quantity : 0;
            });
            return {
                label: product,
                data: data,
                borderColor: colors[index % colors.length],
                backgroundColor: colors[index % colors.length] + '20',
                fill: false,
                tension: 0.4,
                borderWidth: 3
            };
        });

        new Chart(productDemandCtx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: '30-Day Product Demand Forecast'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Predicted Quantity'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                }
            }
        });

        // Product Comparison Chart (Bar Chart)
        const productComparisonCtx = document.getElementById('productComparisonChart').getContext('2d');
        const productTotals = products.map(product => {
            return mlForecast.filter(f => f.product_type === product)
                           .reduce((sum, f) => sum + f.predicted_quantity, 0);
        });

        new Chart(productComparisonCtx, {
            type: 'bar',
            data: {
                labels: products,
                datasets: [{
                    label: 'Total Predicted Demand (30 days)',
                    data: productTotals,
                    backgroundColor: ['#3B82F6', '#10B981', '#F59E0B'],
                    borderColor: ['#2563EB', '#059669', '#D97706'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total Quantity'
                        }
                    }
                }
            }
        });

        // Product Pie Chart
        const productPieCtx = document.getElementById('productPieChart').getContext('2d');
        new Chart(productPieCtx, {
            type: 'doughnut',
            data: {
                labels: products,
                datasets: [{
                    data: productTotals,
                    backgroundColor: ['#3B82F6', '#10B981', '#F59E0B'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Daily Trend Chart (Stacked Area)
        const dailyTrendCtx = document.getElementById('dailyTrendChart').getContext('2d');
        const dailyData = dates.map(date => {
            const dayData = {};
            products.forEach(product => {
                const forecast = mlForecast.find(f => f.date === date && f.product_type === product);
                dayData[product] = forecast ? forecast.predicted_quantity : 0;
            });
            return dayData;
        });

        const dailyDatasets = products.map((product, index) => {
            const colors = ['#3B82F6', '#10B981', '#F59E0B'];
            return {
                label: product,
                data: dailyData.map(day => day[product]),
                backgroundColor: colors[index % colors.length] + '80',
                borderColor: colors[index % colors.length],
                fill: true,
                tension: 0.4
            };
        });

        new Chart(dailyTrendCtx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: dailyDatasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Daily Predicted Quantity'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                }
            }
        });
    } else {
        // Show message if no data
        document.getElementById('productDemandChart').innerHTML = '<div class="text-center text-gray-500 py-8">No ML forecast data available</div>';
        document.getElementById('productComparisonChart').innerHTML = '<div class="text-center text-gray-500 py-8">No ML forecast data available</div>';
        document.getElementById('productPieChart').innerHTML = '<div class="text-center text-gray-500 py-8">No ML forecast data available</div>';
        document.getElementById('dailyTrendChart').innerHTML = '<div class="text-center text-gray-500 py-8">No ML forecast data available</div>';
    }
</script>
@endpush
