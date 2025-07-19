@extends('layouts.dashboard')

@section('header')
    Inventory Analytics & ML Predictions
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6 flex justify-end">
                    <a href="{{ route('admin.customer-segments') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold text-lg px-8 py-3 rounded-xl shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" /></svg>
                        View Customer Segments
                    </a>
                </div>
                <div class="mb-6 flex space-x-4">
                    <a href="{{ route('admin.analytics') }}" class="px-4 py-2 rounded {{ request()->routeIs('admin.analytics') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">Customer Segmentation</a>
                    <a href="{{ route('admin.analytics.sales_predictions') }}" class="px-4 py-2 rounded {{ request()->routeIs('admin.analytics.sales_predictions') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">Sales Predictions</a>
                </div>

                <div class="mb-8 flex flex-col md:flex-row md:items-center md:space-x-4 space-y-4 md:space-y-0">
                    <!-- Upload Dataset Form -->
                    <form action="{{ route('admin.customer-segments.upload-dataset') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                        @csrf
                        <input type="file" name="csv_file" required class="border rounded p-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Upload Dataset</button>
                    </form>
                    <!-- Run Segmentation Button -->
                    <form action="{{ route('admin.customer-segments.run-segmentation') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Run Segmentation</button>
                    </form>
                    <!-- Import Segments Button -->
                    <form action="{{ route('admin.customer-segments.import-segments') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded">Import Segments</button>
                    </form>
                </div>
                @if(session('success'))
                    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
                @endif

                <h3 class="text-lg font-semibold mb-4">Customer Spending Distribution by Segment</h3>
                <p class="text-sm text-gray-600 mb-4">Visualizing spending patterns across {{ count($segmentRecommendations) }} customers</p>
                @php
                    $inputCsvPath = base_path('ml/customer_purchase_data.csv');
                    $inputCsvCount = 0;
                    if (file_exists($inputCsvPath)) {
                        $inputCsvCount = max(0, count(file($inputCsvPath)) - 1); // subtract header
                    }
                @endphp
                <div class="bg-yellow-50 p-4 rounded mb-4">
                    <p class="text-sm text-yellow-800">
                        <strong>Debug Info:</strong>
                        Segment Recommendations Count: {{ count($segmentRecommendations) }} |
                        Customer Segments Count: {{ count($customerSegments) }} |
                        Bread Type Distribution Count: {{ count($breadTypeDistribution) }} |
                        <span class="text-blue-700">Input CSV Customers: {{ $inputCsvCount }}</span>
                    </p>
                </div>
                <div class="mb-8">
                    <canvas id="segmentDistributionChart" width="400" height="200"></canvas>
                </div>

                <h3 class="text-lg font-semibold mb-4">Average Spending by Segment</h3>
                <div class="mb-8">
                    <canvas id="avgSpendingChart" width="400" height="200"></canvas>
                </div>

                <h3 class="text-lg font-semibold mb-4">Top Customers by Spending</h3>
                <div class="mb-8">
                    <canvas id="customerSpendingChart" width="800" height="400"></canvas>
                    <div id="chartDebug" class="text-sm text-gray-600 mt-2"></div>
                    <div id="dataDebug" class="text-sm text-gray-600 mt-2"></div>
                </div>

                <h3 class="text-lg font-semibold mb-4">Bread Type Distribution</h3>
                <div class="mb-8">
                    <canvas id="breadTypeChart" width="400" height="200"></canvas>
                </div>

                <h3 class="text-lg font-semibold mb-4">Customer Location Distribution</h3>
                <div class="mb-8">
                    <canvas id="locationChart" width="400" height="200"></canvas>
                </div>

                <h3 class="text-lg font-semibold mb-4">Average Purchase Frequency by Segment</h3>
                <div class="mb-8">
                    <canvas id="avgPurchaseFrequencyChart" width="400" height="200"></canvas>
                </div>

                <h3 class="text-lg font-semibold mb-4">ML Recommendations by Segment</h3>
                <div class="mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($customerSegments as $segment => $count)
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-blue-800">Segment {{ $segment }}</h4>
                                <p class="text-sm text-blue-600">{{ $count }} customers</p>
                                @if($segment == 'high_value')
                                    <p class="text-xs text-gray-600 mt-2">Recommend: Loyalty rewards and exclusive previews</p>
                                @elseif($segment == 'medium_value')
                                    <p class="text-xs text-gray-600 mt-2">Recommend: Bundle offers and personalized discounts</p>
                                @else
                                    <p class="text-xs text-gray-600 mt-2">Recommend: Win-back campaigns and introductory offers</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Product Sales & Forecast Chart -->
                <h3 class="text-lg font-semibold mb-4">Product Sales & Forecast (ML Predictions)</h3>
                <div class="mb-8">
                    <label for="productSelect" class="block mb-2 font-semibold">Select Product:</label>
                    <select id="productSelect" class="border rounded p-2">
                        @foreach(array_keys($salesHistoryChartData ?? []) as $product)
                            <option value="{{ $product }}">{{ ucfirst($product) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-8">
                    <canvas id="salesChart" width="800" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const salesHistory = @json($salesHistoryChartData ?? []);
    const salesForecast = @json($salesForecastChartData ?? []);
    const productSelect = document.getElementById('productSelect');
    const ctx = document.getElementById('salesChart').getContext('2d');

    function getChartData(product) {
        const history = salesHistory[product] || {};
        const forecast = salesForecast[product] || [];
        const historyDates = Object.keys(history);
        const historyValues = Object.values(history);
        const forecastDates = forecast.map(f => f.date);
        const forecastValues = forecast.map(f => f.predicted);
        return {
            labels: [...historyDates, ...forecastDates],
            datasets: [
                {
                    label: 'Quantity Sold (Last 30 Days)',
                    data: [...historyValues, ...Array(forecastValues.length).fill(null)],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.1)',
                    tension: 0.2,
                },
                {
                    label: 'Predicted (Next 7 Days)',
                    data: [
                        ...Array(historyValues.length).fill(null),
                        ...forecastValues
                    ],
                    borderColor: '#f59e42',
                    backgroundColor: 'rgba(245,158,66,0.1)',
                    borderDash: [5,5],
                    tension: 0.2,
                }
            ]
        };
    }

    let chart;
    function renderChart(product) {
        const data = getChartData(product);
        if (chart) chart.destroy();
        chart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: product.charAt(0).toUpperCase() + product.slice(1) + ' Sales & Forecast' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    if (productSelect && productSelect.value) {
        renderChart(productSelect.value);
        productSelect.addEventListener('change', function() {
            renderChart(this.value);
        });
    }
</script>
@endpush
