@extends('layouts.bakery-manager')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Production Monitoring</h1>
        <p class="mt-1 text-sm text-gray-600">Track and manage your bakery's production in real time.</p>
    </div>
    <div class="text-right">
        <p class="text-sm text-gray-500">Last updated</p>
        <p class="text-sm font-medium text-gray-900">{{ now()->format('M d, Y H:i') }}</p>
    </div>
</div>
@endsection

@section('content')
<!-- Banner -->
<div class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-lg shadow-lg mb-8">
    <div class="px-6 py-8 flex items-center justify-between">
        <div class="text-white">
            <h2 class="text-2xl font-bold mb-2">Production Overview</h2>
            <p class="text-blue-100">Monitor batches, trends, and machine status</p>
        </div>
        <div class="hidden md:block">
            <svg class="w-24 h-24 text-blue-200" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
            </svg>
        </div>
    </div>
</div>
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Batches Today</p>
            <p class="text-2xl font-bold text-gray-900 batches-today">-</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Active Batches</p>
            <p class="text-2xl font-bold text-gray-900 active-batches">-</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Output</p>
            <p class="text-2xl font-bold text-gray-900 output-today">-</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Downtime</p>
            <p class="text-2xl font-bold text-gray-900 downtime-today">-</p>
        </div>
    </div>
</div>
<!-- Quick Actions -->
<div class="mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4">
        <a href="{{ route('bakery.batches.index') }}" class="flex-1 flex items-center p-4 bg-gradient-to-r from-green-500 to-green-600 rounded-lg text-white hover:from-green-600 hover:to-green-700 transition-all duration-200 transform hover:scale-105">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="font-medium">View All Batches</p>
                <p class="text-xs text-green-100">Production List</p>
            </div>
        </a>
        <button onclick="downloadProductionReport()" class="flex-1 flex items-center p-4 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg text-white hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200 transform hover:scale-105">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="font-medium">Download Report</p>
                <p class="text-xs text-yellow-100">Export Data</p>
            </div>
        </button>
    </div>
</div>
<!-- Production Trends Chart -->
<div class="bg-white rounded-xl shadow-lg mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Production Trends</h3>
    </div>
    <div class="p-6">
        <canvas id="productionTrendsChart" height="80"></canvas>
    </div>
</div>
<!-- Recent Batches Table -->
<div class="bg-white rounded-xl shadow-lg mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Recent Batches</h3>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th>Batch Name</th>
                        <th>Status</th>
                        <th>Scheduled Start</th>
                        <th>Actual Start</th>
                        <th>Actual End</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody class="production-batch-tbody">
                    <tr>
                        <td colspan="6" class="text-center text-gray-400 py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No recent batches</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Machine Alerts -->
<div class="bg-white rounded-xl shadow-lg mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Machine Alerts</h3>
    </div>
    <div class="p-6">
        <ul class="list-disc pl-5 text-sm text-gray-700 machine-alert-list">
            <li>Oven 2 scheduled for maintenance at 15:00.</li>
        </ul>
    </div>
</div>
<!-- Activity Timeline -->
<div class="mt-8 bg-white rounded-xl shadow-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
    </div>
    <div class="p-6">
        <div class="flow-root">
            <ul class="-mb-8 activity-timeline">
                <li class="relative pb-8">
                    <div class="relative flex space-x-3">
                        <div>
                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                            <div>
                                <p class="text-sm text-gray-500">Production dashboard accessed</p>
                            </div>
                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                <time>{{ now()->format('M d, H:i') }}</time>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- Live Stats Cards ---
    function fetchProductionStatsLive() {
        fetch('/api/production-live')
            .then(res => res.json())
            .then(data => {
                document.querySelector('.batches-today').textContent = data.batches_today ?? '-';
                document.querySelector('.active-batches').textContent = data.active ?? '-';
                document.querySelector('.output-today').textContent = data.output ?? '-';
                document.querySelector('.downtime-today').textContent = data.downtime ?? '0';
            });
    }
    // --- Production Trends Chart ---
    function fetchProductionTrends() {
        fetch('/api/production-trends')
            .then(res => res.json())
            .then(data => {
                const labels = data.map(item => item.date);
                const values = data.map(item => item.total_output);
                const ctx = document.getElementById('productionTrendsChart').getContext('2d');
                if (window.productionTrendsChart) window.productionTrendsChart.destroy();
                window.productionTrendsChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Output',
                            data: values,
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99,102,241,0.1)',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 4,
                            pointBackgroundColor: '#6366f1'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Date'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Output'
                                },
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
    }
    // --- Live Recent Batches Table ---
    function fetchRecentBatchesLive() {
        fetch('/api/production-live')
            .then(res => res.json())
            .then(data => {
                const tbody = document.querySelector('.production-batch-tbody');
                tbody.innerHTML = '';
                if (!data.batches || data.batches.length === 0) {
                    tbody.innerHTML = `<tr><td colspan='6' class='text-center text-gray-400 py-8'>No recent batches</td></tr>`;
                } else {
                    data.batches.forEach(batch => {
                        function fmt(dt) {
                            if (!dt) return '-';
                            const d = new Date(dt);
                            if (isNaN(d)) return dt;
                            return d.toLocaleString('en-US', {
                                month: '2-digit',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                        }
                        let badgeClass = 'bg-gray-200 text-gray-800';
                        if (batch.status === 'active' || batch.status === 'Active') badgeClass = 'bg-blue-200 text-blue-800';
                        if (batch.status === 'completed' || batch.status === 'Completed') badgeClass = 'bg-green-200 text-green-800';
                        if (batch.status === 'delayed' || batch.status === 'Delayed') badgeClass = 'bg-red-200 text-red-800';
                        tbody.innerHTML += `<tr>
                        <td>${batch.name}</td>
                        <td><span class='px-2 py-1 rounded ${badgeClass}'>${batch.status}</span></td>
                        <td>${fmt(batch.scheduled_start)}</td>
                        <td>${fmt(batch.actual_start)}</td>
                        <td>${fmt(batch.actual_end)}</td>
                        <td title='${batch.notes ?? ''}'>${batch.notes ? batch.notes.substring(0, 30) + (batch.notes.length > 30 ? '...' : '') : '-'}</td>
                    </tr>`;
                    });
                }
            });
    }
    // --- Live Machine Alerts ---
    function fetchMachineAlertsLive() {
        fetch('/api/machines-live')
            .then(res => res.json())
            .then(data => {
                const alertList = document.querySelector('.machine-alert-list');
                alertList.innerHTML = '';
                if (data.alerts && data.alerts.length) {
                    data.alerts.forEach(alert => {
                        alertList.innerHTML += `<li>${alert}</li>`;
                    });
                } else {
                    alertList.innerHTML = '<li class="text-gray-400">No machine alerts</li>';
                }
            });
    }
    // --- Live Activity Timeline ---
    function fetchProductionActivityLive() {
        fetch('/api/production-activity')
            .then(res => res.json())
            .then(data => {
                const timeline = document.querySelector('.activity-timeline');
                timeline.innerHTML = '';
                if (data.notifications && data.notifications.length) {
                    data.notifications.forEach(note => {
                        timeline.innerHTML += `<li class="relative pb-8">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-gray-500">${note}</p>
                                    </div>
                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                        <time>${new Date().toLocaleString()}</time>
                                    </div>
                                </div>
                            </div>
                        </li>`;
                    });
                } else {
                    timeline.innerHTML = '<li class="relative pb-8 text-gray-400">No recent activity</li>';
                }
            });
    }
    // --- Download report function ---
    function downloadProductionReport() {
        window.location.href = '/api/production-report';
    }
    // --- Initial fetch and polling ---
    fetchProductionStatsLive();
    fetchRecentBatchesLive();
    fetchProductionTrends();
    fetchMachineAlertsLive();
    fetchProductionActivityLive();
    // setInterval(fetchProductionStatsLive, 60000); // polling disabled
    // setInterval(fetchRecentBatchesLive, 60000); // polling disabled
    // setInterval(fetchProductionTrendsLive, 60000); // polling disabled
    // setInterval(fetchMachineAlertsLive, 60000); // polling disabled
    // setInterval(fetchProductionActivityLive, 60000); // polling disabled
</script>
@endpush