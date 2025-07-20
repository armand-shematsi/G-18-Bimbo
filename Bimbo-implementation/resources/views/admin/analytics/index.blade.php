@extends('layouts.dashboard')

@section('header')
    Analytics Overview
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Quantity Sold (Last 30 Days)</h3>
                    <label for="soldProductSelect" class="block mb-2 font-semibold">Select Product:</label>
                    <select id="soldProductSelect" class="border rounded p-2 mb-4">
                        @foreach(array_keys($salesHistoryChartData ?? []) as $product)
                            <option value="{{ strtolower($product) }}">{{ ucfirst($product) }}</option>
                        @endforeach
                    </select>
                    <canvas id="soldChart" width="800" height="400"></canvas>
                </div>
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Predicted Sales (Next 7 Days)</h3>
                    <label for="predProductSelect" class="block mb-2 font-semibold">Select Product:</label>
                    <select id="predProductSelect" class="border rounded p-2 mb-4">
                        @foreach(array_keys($salesForecastChartData ?? []) as $product)
                            <option value="{{ strtolower($product) }}">{{ ucfirst($product) }}</option>
                        @endforeach
                    </select>
                    <canvas id="predChart" width="800" height="400"></canvas>
                </div>
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Total Sales vs. Predicted Sales (Aggregate)</h3>
                    <canvas id="aggregateSalesChart" width="800" height="400"></canvas>
                </div>
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Top Products Sold (Last 30 Days)</h3>
                    <canvas id="topProductsBarChart" width="800" height="400"></canvas>
                </div>
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Order Status Distribution (Last 30 Days)</h3>
                    <canvas id="orderStatusPieChart" width="250" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Quantity Sold Chart
const salesHistory = @json($salesHistoryChartData ?? []);
const soldProductSelect = document.getElementById('soldProductSelect');
const soldCtx = document.getElementById('soldChart').getContext('2d');
function getSoldChartData(product) {
    const history = salesHistory[product] || {};
    const historyDates = Object.keys(history);
    const historyValues = Object.values(history);
    return {
        labels: historyDates,
        datasets: [
            {
                label: 'Quantity Sold',
                data: historyValues,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                tension: 0.2,
            }
        ]
    };
}
let soldChart;
function renderSoldChart(product) {
    const data = getSoldChartData(product);
    if (soldChart) soldChart.destroy();
    soldChart = new Chart(soldCtx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: product.charAt(0).toUpperCase() + product.slice(1) + ' Quantity Sold' },
                tooltip: { enabled: true },
            },
            scales: {
                x: { title: { display: true, text: 'Date' } },
                y: { beginAtZero: true, title: { display: true, text: 'Quantity Sold' } }
            }
        }
    });
}
if (soldProductSelect && soldProductSelect.value) {
    renderSoldChart(soldProductSelect.value);
    soldProductSelect.addEventListener('change', function() {
        renderSoldChart(this.value);
    });
}
// Predicted Sales Chart
const salesForecast = @json($salesForecastChartData ?? []);
const predProductSelect = document.getElementById('predProductSelect');
const predCtx = document.getElementById('predChart').getContext('2d');
function getPredChartData(product) {
    const forecast = salesForecast[product] || [];
    const forecastDates = forecast.map(f => f.date);
    const forecastValues = forecast.map(f => f.predicted);
    return {
        labels: forecastDates,
        datasets: [
            {
                label: 'Predicted Sales',
                data: forecastValues,
                borderColor: '#f59e42',
                backgroundColor: 'rgba(245,158,66,0.1)',
                tension: 0.2,
            }
        ]
    };
}
let predChart;
function renderPredChart(product) {
    const data = getPredChartData(product);
    if (predChart) predChart.destroy();
    predChart = new Chart(predCtx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: product.charAt(0).toUpperCase() + product.slice(1) + ' Predicted Sales' },
                tooltip: { enabled: true },
            },
            scales: {
                x: { title: { display: true, text: 'Date' } },
                y: { beginAtZero: true, title: { display: true, text: 'Predicted Sales' } }
            }
        }
    });
}
if (predProductSelect && predProductSelect.value) {
    renderPredChart(predProductSelect.value);
    predProductSelect.addEventListener('change', function() {
        renderPredChart(this.value);
    });
}
// Aggregate Sales vs. Predicted Sales
const aggregateSales = @json($aggregateSalesHistory ?? []);
const aggregatePredicted = @json($aggregatePredicted ?? []);
const aggLabels = [...Object.keys(aggregateSales), ...Object.keys(aggregatePredicted)];
const aggSold = Object.values(aggregateSales);
const aggPred = Object.values(aggregatePredicted);
new Chart(document.getElementById('aggregateSalesChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: aggLabels,
        datasets: [
            {
                label: 'Total Sales (Last 30 Days)',
                data: [...aggSold, ...Array(aggPred.length).fill(null)],
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.1)',
                tension: 0.2,
            },
            {
                label: 'Predicted Sales (Next 7 Days)',
                data: [...Array(aggSold.length).fill(null), ...aggPred],
                borderColor: '#f59e42',
                backgroundColor: 'rgba(245,158,66,0.1)',
                borderDash: [5,5],
                tension: 0.2,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {legend: {position: 'top'}},
        scales: {
            x: { title: { display: true, text: 'Date' } },
            y: { title: { display: true, text: 'Sales' }, beginAtZero: true }
        }
    }
});
// Top Products Sold (Bar)
const topProductsBar = @json($topProductsBar ?? []);
new Chart(document.getElementById('topProductsBarChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: topProductsBar.map(p => p.product_name),
        datasets: [{
            label: 'Quantity Sold',
            data: topProductsBar.map(p => p.total_sold),
            backgroundColor: '#10b981',
        }]
    },
    options: {
        responsive: true,
        plugins: {legend: {display: false}},
        scales: {
            x: { title: { display: true, text: 'Product' } },
            y: { title: { display: true, text: 'Quantity Sold' }, beginAtZero: true }
        }
    }
});
// Order Status Distribution (Pie)
const orderStatusDist = @json($orderStatusDist ?? []);
new Chart(document.getElementById('orderStatusPieChart').getContext('2d'), {
    type: 'pie',
    data: {
        labels: orderStatusDist.map(s => s.status),
        datasets: [{
            label: 'Orders',
            data: orderStatusDist.map(s => s.count),
            backgroundColor: ['#10b981', '#f59e42', '#6366f1', '#ef4444', '#fbbf24'],
        }]
    },
    options: {
        responsive: true,
        plugins: {legend: {position: 'top'}},
        // Pie chart does not have axes, so no axis labels needed
    }
});
</script>
@endpush
