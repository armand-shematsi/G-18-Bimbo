@extends("layouts.dashboard")

@section('header')
    Inventory Dashboard
@endsection

@section('content')
<div class="space-y-6">
    <!-- Low Stock Alert Banner -->
    @if(isset($lowStockItems) && count($lowStockItems) > 0)
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
            <div class="font-bold mb-2">Low Stock Alert!</div>
            <ul class="list-disc pl-6">
                @foreach($lowStockItems as $item)
                    <li>
                        <span class="font-semibold">{{ $item->item_name }}</span> ({{ $item->quantity }} {{ $item->unit }}) is at or below its reorder level ({{ $item->reorder_level }} {{ $item->unit }})
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Items -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Items</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $totalItems ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Low Stock Alerts</dt>
                            <dd class="text-lg font-medium text-yellow-600">{{ $lowStockCount ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Out of Stock</dt>
                            <dd class="text-lg font-medium text-red-600">{{ $outOfStockCount ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Deliveries -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Recent Deliveries</dt>
                            <dd class="text-lg font-medium text-green-600">{{ $recentDeliveriesCount ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add after statistics cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-5">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Total Inventory Value</h3>
            <div class="text-2xl font-bold text-green-700">₦{{ number_format($totalInventoryValue, 2) }}</div>
        </div>
        <div class="bg-white shadow rounded-lg p-5">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Stock Status Distribution</h3>
            <canvas id="statusPieChart" height="120"></canvas>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-5">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Stock Level per Item</h3>
            <canvas id="stockLevelBarChart" height="120"></canvas>
        </div>
        <div class="bg-white shadow rounded-lg p-5">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Stock Movement Trends (7 days)</h3>
            <canvas id="movementLineChart" height="120"></canvas>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Stock Level Table -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Stock Level Overview</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reorder Level</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($inventoryItems ?? [] as $item)
                                <tr class="{{ $item->needsReorder() ? 'bg-red-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->item_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->item_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }} {{ $item->unit }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->reorder_level }} {{ $item->unit }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $item->quantity > $item->reorder_level ? 'bg-green-100 text-green-800' : ($item->quantity > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $item->quantity > $item->reorder_level ? 'In Stock' : ($item->quantity > 0 ? 'Low Stock' : 'Out of Stock') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-6 py-2 bg-gray-50">
                                        <button type="button" class="text-xs text-blue-600 hover:underline" onclick="const el = this.nextElementSibling; el.style.display = el.style.display === 'none' ? 'block' : 'none';">
                                            {{ __('Show/Hide Movement History') }}
                                        </button>
                                        <div style="display:none;">
                                            @if($item->movements->isEmpty())
                                                <div class="text-gray-500 text-xs mt-2">No movement history available for this item.</div>
                                            @else
                                                <div class="overflow-x-auto mt-2">
                                                    <table class="min-w-full divide-y divide-gray-200 text-xs">
                                                        <thead>
                                                            <tr>
                                                                <th class="px-2 py-1 text-left font-medium text-gray-500 uppercase">Date</th>
                                                                <th class="px-2 py-1 text-left font-medium text-gray-500 uppercase">Type</th>
                                                                <th class="px-2 py-1 text-left font-medium text-gray-500 uppercase">Quantity</th>
                                                                <th class="px-2 py-1 text-left font-medium text-gray-500 uppercase">User</th>
                                                                <th class="px-2 py-1 text-left font-medium text-gray-500 uppercase">Note</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-200">
                                                            @foreach($item->movements as $movement)
                                                                <tr>
                                                                    <td class="px-2 py-1 whitespace-nowrap">{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                                                                    <td class="px-2 py-1 whitespace-nowrap capitalize">{{ $movement->type }}</td>
                                                                    <td class="px-2 py-1 whitespace-nowrap">{{ $movement->quantity }}</td>
                                                                    <td class="px-2 py-1 whitespace-nowrap">{{ $movement->user ? $movement->user->name : 'N/A' }}</td>
                                                                    <td class="px-2 py-1 whitespace-nowrap">{{ $movement->note }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No inventory items found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Panel -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="#" class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add New Item
                        </a>
                        <a href="#" class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                            </svg>
                            Record Delivery
                        </a>
                        <a href="#" class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Generate Report
                        </a>
                        <a href="#" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Low Stock Alerts</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reorder Level</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($lowStockItems ?? [] as $item)
                        <tr class="bg-red-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->item_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-semibold">{{ $item->quantity }} {{ $item->unit }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->reorder_level }} {{ $item->unit }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->updated_at ? $item->updated_at->format('Y-m-d H:i') : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <button class="text-indigo-600 hover:text-indigo-900 mr-3">Reorder</button>
                                <button class="text-gray-600 hover:text-gray-900">Update</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No low stock alerts at this time.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Inventory Trend Graph -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Inventory Trend (Last 30 Days)</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg" id="chartContainer">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">Chart will be implemented with Chart.js or similar library</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Deliveries -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Deliveries</h3>
            <div class="space-y-4">
                @forelse($recentDeliveries ?? [] as $delivery)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">{{ $delivery->item_name ?? 'Delivery Item' }}</p>
                            <p class="text-sm text-gray-500">Quantity: {{ $delivery->quantity ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ $delivery->delivery_date ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-400">Supplier: {{ $delivery->supplier ?? 'N/A' }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No recent deliveries</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Chart.js for Inventory Trend Graph -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Use real sales and forecast data if available
    const salesHistory = @json($salesHistoryChartData ?? []);
    const salesForecast = @json($salesForecastChartData ?? []);
    const productNames = Object.keys(salesHistory);
    let selectedProduct = productNames.length > 0 ? productNames[0] : null;

    const chartContainer = document.getElementById('chartContainer');
    chartContainer.innerHTML = '<select id="productSelect" class="mb-4 p-2 border rounded"></select><canvas id="inventoryTrendChart" style="height: 256px;"></canvas>';

    const productSelect = document.getElementById('productSelect');
    productNames.forEach(product => {
        const option = document.createElement('option');
        option.value = product;
        option.text = product.charAt(0).toUpperCase() + product.slice(1);
        productSelect.appendChild(option);
    });
    if (selectedProduct) productSelect.value = selectedProduct;

    const ctx = document.getElementById('inventoryTrendChart').getContext('2d');
    let chart;

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

    if (selectedProduct) {
        renderChart(selectedProduct);
        productSelect.addEventListener('change', function() {
            renderChart(this.value);
        });
    }
});
</script>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Stock Status Pie Chart
    new Chart(document.getElementById('statusPieChart'), {
        type: 'pie',
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: [{{ $statusCounts['in_stock'] ?? 0 }}, {{ $statusCounts['low_stock'] ?? 0 }}, {{ $statusCounts['out_of_stock'] ?? 0 }}],
                backgroundColor: ['#34d399', '#fbbf24', '#f87171'],
            }]
        },
        options: {responsive: true}
    });
    // Stock Level Bar Chart
    new Chart(document.getElementById('stockLevelBarChart'), {
        type: 'bar',
        data: {
            labels: {!! $stockLevelChartData->pluck('item_name')->toJson() !!},
            datasets: [{
                label: 'Quantity',
                data: {!! $stockLevelChartData->pluck('quantity')->toJson() !!},
                backgroundColor: '#60a5fa',
            }]
        },
        options: {responsive: true, indexAxis: 'y'}
    });
    // Movement Trends Line Chart
    new Chart(document.getElementById('movementLineChart'), {
        type: 'line',
        data: {
            labels: {!! $dates->toJson() !!},
            datasets: [
                {
                    label: 'Stock In',
                    data: {!! $stockInData->toJson() !!},
                    borderColor: '#34d399',
                    backgroundColor: 'rgba(52,211,153,0.2)',
                    fill: true
                },
                {
                    label: 'Stock Out',
                    data: {!! $stockOutData->toJson() !!},
                    borderColor: '#f87171',
                    backgroundColor: 'rgba(248,113,113,0.2)',
                    fill: true
                }
            ]
        },
        options: {responsive: true}
    });
</script>
@endsection
