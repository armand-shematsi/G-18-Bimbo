@extends('layouts.retail-manager')

@section('header')
    <div class="mb-10 text-center">
        <h1 class="font-extrabold text-3xl text-gray-900 leading-tight tracking-tight mb-2">Retail Manager Dashboard</h1>
        <p class="text-gray-500 text-lg">Quick summary of your most important metrics</p>
    </div>
@endsection

@section('fullpage')
<div class="flex min-h-screen bg-gradient-to-br from-blue-50 to-white items-center justify-center">
    <main class="w-full max-w-5xl mx-auto p-6">
        <!-- Debug Section -->
        @if(isset($totalOrders) && isset($todayOrders))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
            <strong>Debug Info:</strong> Total Orders in System: {{ $totalOrders }}, Orders Today: {{ $todayOrders }}
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white p-8 rounded-2xl shadow flex flex-col items-center text-center">
                <div class="text-2xl font-bold text-blue-700 mb-1">${{ number_format($salesToday, 2) }}</div>
                <div class="font-semibold text-gray-700">Total Sales Today</div>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow flex flex-col items-center text-center">
                <div class="text-2xl font-bold text-green-700 mb-1">{{ $ordersToday }}</div>
                <div class="font-semibold text-gray-700">Total Orders Today</div>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow flex flex-col items-center text-center">
                <div class="text-2xl font-bold text-purple-700 mb-1">${{ number_format($inventoryValue, 2) }}</div>
                <div class="font-semibold text-gray-700">Inventory Value</div>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow flex flex-col items-center text-center">
                <div class="text-2xl font-bold text-red-600 mb-1">{{ $lowStockCount }}</div>
                <div class="font-semibold text-gray-700">Low Stock Items</div>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow flex flex-col items-center text-center">
                <div class="text-2xl font-bold text-yellow-600 mb-1">{{ $pendingOrders }}</div>
                <div class="font-semibold text-gray-700">Pending Orders</div>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow flex flex-col items-center text-center">
                <div class="text-2xl font-bold text-gray-700 mb-1">${{ number_format($returnsToday, 2) }}</div>
                <div class="font-semibold text-gray-700">Returns/Refunds Today</div>
            </div>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow max-w-2xl mx-auto mb-8">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Top-Selling Products</h2>
            <ul class="divide-y divide-gray-200">
                @foreach($topSellingProducts as $product)
                    <li class="py-2 flex justify-between">
                        <span>{{ $product->name }}</span>
                        <span class="font-semibold">{{ $product->sold }} sold</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Analytics Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Sales Trend Chart -->
            <div class="bg-white p-8 rounded-2xl shadow">
                <h2 class="text-xl font-bold mb-6 text-gray-800">Sales Trend (Last 7 Days)</h2>
                <canvas id="salesTrendChart" height="300"></canvas>
            </div>

            <!-- Inventory Trends Chart -->
            <div class="bg-white p-8 rounded-2xl shadow">
                <h2 class="text-xl font-bold mb-6 text-gray-800">Inventory Trends (Last 7 Days)</h2>
                <canvas id="inventoryTrendsChart" height="300"></canvas>
            </div>

            <!-- Bread Orders Trend Chart -->
            <div class="bg-white p-8 rounded-2xl shadow col-span-1 lg:col-span-2">
                <h2 class="text-xl font-bold mb-6 text-gray-800">Bread Orders Trend (Last 7 Days)</h2>
                <canvas id="breadOrdersTrendChart" height="300"></canvas>
            </div>

            <!-- Order Status Distribution -->
            <div class="bg-white p-8 rounded-2xl shadow">
                <h2 class="text-xl font-bold mb-6 text-gray-800">Order Status Distribution</h2>
                <canvas id="orderStatusChart" height="300"></canvas>
            </div>
        </div>

        <!-- Additional Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Daily Orders Chart -->
            <div class="bg-white p-8 rounded-2xl shadow">
                <h2 class="text-xl font-bold mb-6 text-gray-800">Daily Orders (Last 7 Days)</h2>
                <canvas id="dailyOrdersChart" height="300"></canvas>
            </div>

            <!-- Inventory Status -->
            <div class="bg-white p-8 rounded-2xl shadow">
                <h2 class="text-xl font-bold mb-6 text-gray-800">Inventory Status</h2>
                <canvas id="inventoryStatusChart" height="300"></canvas>
            </div>
        </div>
    </main>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Trend Chart
    const salesCtx = document.getElementById('salesTrendChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Daily Sales ($)',
                data: [0, 0, 0, 0, 0, 0, 0],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Inventory Trends Chart
    const inventoryTrendsData = @json($inventoryTrends);
    const inventoryTrendsLabels = inventoryTrendsData.map(item => item.date);
    const inventoryTrendsTotals = inventoryTrendsData.map(item => item.total);
    const inventoryTrendsCtx = document.getElementById('inventoryTrendsChart').getContext('2d');
    new Chart(inventoryTrendsCtx, {
        type: 'line',
        data: {
            labels: inventoryTrendsLabels,
            datasets: [{
                label: 'Inventory Level',
                data: inventoryTrendsTotals,
                borderColor: 'rgb(139, 92, 246)',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Bread Orders Trend Chart
    const breadOrderTrendsData = @json($breadOrderTrends);
    const breadOrderTrendsLabels = breadOrderTrendsData.map(item => item.date);
    const breadOrderTrendsCounts = breadOrderTrendsData.map(item => item.count);
    const breadOrdersTrendCtx = document.getElementById('breadOrdersTrendChart').getContext('2d');
    new Chart(breadOrdersTrendCtx, {
        type: 'line',
        data: {
            labels: breadOrderTrendsLabels,
            datasets: [{
                label: 'Bread Orders',
                data: breadOrderTrendsCounts,
                borderColor: 'rgb(251, 191, 36)',
                backgroundColor: 'rgba(251, 191, 36, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Order Status Distribution Chart
    const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'],
            datasets: [{
                data: [0, 0, 0, 0, 0],
                backgroundColor: [
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 99, 132, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 205, 86, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Daily Orders Chart
    const ordersCtx = document.getElementById('dailyOrdersChart').getContext('2d');
    new Chart(ordersCtx, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Orders',
                data: [0, 0, 0, 0, 0, 0, 0],
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgba(34, 197, 94, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Inventory Status Chart
    const inventoryCtx = document.getElementById('inventoryStatusChart').getContext('2d');
    new Chart(inventoryCtx, {
        type: 'pie',
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: [0, 0, 0],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(255, 99, 132, 0.8)'
                ],
                borderColor: [
                    'rgba(34, 197, 94, 1)',
                    'rgba(255, 205, 86, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>
@endsection
