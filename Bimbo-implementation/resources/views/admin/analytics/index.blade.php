@extends('layouts.dashboard')

@section('header')
    Inventory Analytics & ML Predictions
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @if(isset($adminInventoryStats))
                    <div class="mt-2">
                        <h2 class="text-2xl font-bold mb-4">Inventory Analytics & ML Predictions</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-white p-4 rounded shadow">
                                <div class="text-gray-500 text-xs">Total Items</div>
                                <div class="text-2xl font-bold">{{ $adminInventoryStats['total'] }}</div>
                            </div>
                            <div class="bg-white p-4 rounded shadow">
                                <div class="text-gray-500 text-xs">Available</div>
                                <div class="text-2xl font-bold">{{ $adminInventoryStats['available'] }}</div>
                            </div>
                            <div class="bg-white p-4 rounded shadow">
                                <div class="text-gray-500 text-xs">Low Stock</div>
                                <div class="text-2xl font-bold">{{ $adminInventoryStats['low_stock'] }}</div>
                            </div>
                            <div class="bg-white p-4 rounded shadow">
                                <div class="text-gray-500 text-xs">Out of Stock</div>
                                <div class="text-2xl font-bold">{{ $adminInventoryStats['out_of_stock'] }}</div>
                            </div>
                        </div>
                        <div class="mb-6">
                            <div class="bg-white p-4 rounded shadow">
                                <div class="text-gray-500 text-xs mb-2">ML Predictions</div>
                                <ul>
                                    @foreach($adminInventoryPredictions as $prediction)
                                        <li class="mb-2">
                                            <span class="font-medium">{{ $prediction['item_name'] ?? 'Item' }}:</span>
                                            Predicted value: <span class="text-blue-600">{{ $prediction['predicted'] ?? json_encode($prediction) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="mb-6">
                            <div class="bg-white p-4 rounded shadow">
                                <div class="text-gray-500 text-xs">Total Inventory Value</div>
                                <div class="text-xl font-bold">₦{{ number_format($adminTotalInventoryValue, 2) }}</div>
                            </div>
                        </div>
                        <!-- Sales History and Forecast Chart -->
                        <div class="mb-6">
                            <div class="bg-white p-4 rounded shadow">
                                <div class="text-gray-500 text-xs mb-2">Quantity Sold vs Time & 7-Day Forecast</div>
                                <select id="productSelect" class="mb-4 p-2 border rounded">
                                    @foreach(array_keys($salesHistoryChartData ?? []) as $product)
                                        <option value="{{ $product }}">{{ ucfirst($product) }}</option>
                                    @endforeach
                                </select>
                                <canvas id="salesChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                @endif
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
