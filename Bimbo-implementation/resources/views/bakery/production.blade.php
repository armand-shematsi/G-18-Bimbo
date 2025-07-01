@extends('layouts.bakery-manager')

@section('header')
Production Monitoring
@endsection

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:space-x-4 space-y-2 md:space-y-0">
    <a href="{{ route('bakery.production.start') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Start Production</a>
    <a href="{{ route('bakery.batches.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">Production Batches</a>
    <a href="{{ route('bakery.schedule') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">Production Schedule</a>
</div>

<!-- Notification Area -->
<div id="notification-area" class="mb-4"></div>

<!-- Filter/Search Controls -->
<div class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-2 md:space-y-0 mb-6">
    <div>
        <label for="filter-status" class="block text-sm font-medium text-gray-700">Status</label>
        <select id="filter-status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">All</option>
            <option value="planned">Planned</option>
            <option value="active">Active</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>
    <div>
        <label for="filter-date" class="block text-sm font-medium text-gray-700">Date</label>
        <input type="date" id="filter-date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>
    <div>
        <label for="filter-product" class="block text-sm font-medium text-gray-700">Product</label>
        <input type="text" id="filter-product" placeholder="Product name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>
    <button id="export-btn" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-4 md:mt-0">Export CSV</button>
</div>

<!-- Chart Area -->
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Production Trends</h3>
    <canvas id="productionChart" height="80"></canvas>
</div>

<!-- Responsive Table for Batches -->
<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200" id="batches-table">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheduled Start</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actual Start</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actual End</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
            </tr>
        </thead>
        <tbody id="batches-tbody" class="bg-white divide-y divide-gray-200">
            <!-- Populated by JS -->
        </tbody>
    </table>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function fetchBatches() {
        const status = document.getElementById('filter-status').value;
        const date = document.getElementById('filter-date').value;
        const product = document.getElementById('filter-product').value;
        let url = `{{ route('bakery.production-batches.api') }}?`;
        if (status) url += `status=${status}&`;
        if (date) url += `date=${date}&`;
        if (product) url += `product=${encodeURIComponent(product)}&`;
        fetch(url)
            .then(res => res.json())
            .then(data => {
                renderBatches(data);
                updateChart(data);
            });
    }

    function renderBatches(batches) {
        const tbody = document.getElementById('batches-tbody');
        tbody.innerHTML = '';
        if (!batches.length) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-gray-500">No batches found</td></tr>`;
            return;
        }
        batches.forEach(batch => {
            tbody.innerHTML += `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${batch.name}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">${batch.status}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">${batch.scheduled_start ?? '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">${batch.actual_start ?? '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">${batch.actual_end ?? '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">${batch.notes ?? '-'}</td>
            </tr>
        `;
        });
    }

    function updateChart(batches) {
        // Example: show number of batches per status
        const statusCounts = batches.reduce((acc, b) => {
            acc[b.status] = (acc[b.status] || 0) + 1;
            return acc;
        }, {});
        const ctx = document.getElementById('productionChart').getContext('2d');
        if (window.productionChart) window.productionChart.destroy();
        window.productionChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(statusCounts),
                datasets: [{
                    label: 'Batches',
                    data: Object.values(statusCounts),
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e42', '#ef4444'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    document.getElementById('filter-status').addEventListener('change', fetchBatches);
    document.getElementById('filter-date').addEventListener('change', fetchBatches);
    document.getElementById('filter-product').addEventListener('input', fetchBatches);

    // Export CSV
    function exportCSV() {
        const rows = Array.from(document.querySelectorAll('#batches-table tr'));
        const csv = rows.map(row => Array.from(row.children).map(cell => '"' + cell.innerText.replace(/"/g, '""') + '"').join(',')).join('\n');
        const blob = new Blob([csv], {
            type: 'text/csv'
        });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'production_batches.csv';
        a.click();
        URL.revokeObjectURL(url);
    }
    document.getElementById('export-btn').addEventListener('click', exportCSV);

    // Notification example (placeholder)
    function showNotification(msg, type = 'info') {
        const area = document.getElementById('notification-area');
        area.innerHTML = `<div class="p-4 mb-4 text-sm text-${type === 'error' ? 'red' : 'green'}-700 bg-${type === 'error' ? 'red' : 'green'}-100 rounded-lg">${msg}</div>`;
        setTimeout(() => {
            area.innerHTML = '';
        }, 5000);
    }

    // Initial fetch and polling for live updates
    fetchBatches();
    setInterval(fetchBatches, 15000); // Poll every 15s
</script>
@endpush

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Production Overview Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Production</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Total Loaves</p>
                                <p class="text-2xl font-bold text-primary">1,250</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Target</p>
                                <p class="text-2xl font-bold text-gray-900">1,500</p>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 83%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Quality Metrics Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quality Metrics</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Quality Score</p>
                                <p class="text-2xl font-bold text-green-600">98%</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Rejection Rate</p>
                                <p class="text-2xl font-bold text-red-600">2%</p>
                            </div>
                        </div>
                    </div>

                    <!-- Efficiency Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Efficiency</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Production Rate</p>
                                <p class="text-2xl font-bold text-primary">125 loaves/hr</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Downtime</p>
                                <p class="text-2xl font-bold text-gray-900">15 min</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Production Line Status -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Production Line Status</h3>
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Line</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Output</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Efficiency</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Line 1</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Running</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">White Bread</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">450 loaves</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">95%</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Line 2</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Maintenance</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Whole Wheat</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">0 loaves</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Line 3</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Running</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sourdough</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">380 loaves</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">92%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection