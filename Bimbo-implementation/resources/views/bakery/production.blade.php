@extends('layouts.bakery-manager')

@section('header')
<div class="flex flex-col md:flex-row justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        <img src="/images/baguette.jpg" alt="Bakery Logo" class="w-12 h-12 rounded-full shadow-md border-2 border-sky-400 bg-white object-cover">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Production Monitoring</h1>
            <p class="mt-1 text-base text-gray-600 font-medium">Track and manage your bakery's production in real time.</p>
        </div>
    </div>
    <div class="text-right mt-4 md:mt-0">
        <p class="text-sm text-gray-500">Last updated</p>
        <p class="text-sm font-medium text-gray-900">{{ now()->format('M d, Y H:i') }}</p>
    </div>
</div>
@endsection

@section('content')
<!-- Banner -->
<div class="bg-blue-500 rounded-2xl shadow-xl mb-10 overflow-hidden flex flex-col md:flex-row items-center justify-between px-8 py-10 relative">
    <div class="text-white z-10">
        <h2 class="text-3xl md:text-4xl font-extrabold mb-2 drop-shadow">Production Overview</h2>
        <p class="text-lg text-blue-100 font-medium">Monitor batches, trends, and machine status.</p>
    </div>
    <div class="hidden md:block absolute right-8 top-1/2 -translate-y-1/2 opacity-30 z-0">
        <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
        </svg>
    </div>
</div>
<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-12">
    <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-blue-400 flex flex-col items-start hover:shadow-2xl transition-shadow duration-200 group">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-semibold text-gray-600">Batches Today</p>
                <p class="text-2xl font-extrabold text-gray-900 batches-today">-</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-green-400 flex flex-col items-start hover:shadow-2xl transition-shadow duration-200 group">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-semibold text-gray-600">Active Batches</p>
                <p class="text-2xl font-extrabold text-gray-900 active-batches">-</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-yellow-400 flex flex-col items-start hover:shadow-2xl transition-shadow duration-200 group">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-semibold text-gray-600">Output</p>
                <p class="text-2xl font-extrabold text-gray-900 output-today">-</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-red-400 flex flex-col items-start hover:shadow-2xl transition-shadow duration-200 group">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-1.414-1.414L12 9.172 7.05 4.222l-1.414 1.414L10.828 12l-5.192 5.192 1.414 1.414L12 14.828l4.95 4.95 1.414-1.414L13.172 12z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-semibold text-gray-600">Downtime</p>
                <p class="text-2xl font-extrabold text-gray-900 downtime-today">-</p>
            </div>
        </div>
    </div>
</div>
<!-- Quick Actions -->
<div class="mb-8">
    <div class="bg-white rounded-2xl shadow-xl p-6 flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4">
        <a href="{{ route('bakery.batches.index') }}" class="flex-1 flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105 shadow-md">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="font-semibold">View All Batches</p>
                <p class="text-xs text-blue-100">Production List</p>
            </div>
        </a>
        <button onclick="downloadProductionReport()" class="flex-1 flex items-center p-4 bg-gradient-to-r from-blue-400 to-blue-500 rounded-lg text-white hover:from-blue-500 hover:to-blue-600 transition-all duration-200 transform hover:scale-105 shadow-md">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="font-semibold">Download Report</p>
                <p class="text-xs text-blue-100">Export Data</p>
            </div>
        </button>
    </div>
</div>
<!-- Production Trends Chart -->
<div class="bg-white rounded-2xl shadow-xl mb-8">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-900">Production Trends</h3>
        <div class="flex gap-2">
            <select id="trendDaysSelect" class="border rounded px-2 py-1 text-sm">
                <option value="7">Last 7 Days</option>
                <option value="30">Last 30 Days</option>
            </select>
        </div>
    </div>
    <div class="p-6">
        <canvas id="productionTrendsChart" height="80"></canvas>
    </div>
</div>
<!-- Recent Batches Table -->
<div class="bg-white rounded-2xl shadow-xl mb-8">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-900">Recent Batches</h3>
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
<!-- Activity Timeline (Real-time) -->
<div class="mt-8 bg-white rounded-2xl shadow-xl">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-900">Recent Activity</h3>
    </div>
    <div class="p-6">
        <div class="flow-root">
            <ul id="production-activity-list" class="-mb-8 activity-timeline min-h-[60px]">
                <li class="text-gray-400">Loading...</li>
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
    // Helper: Convert UTC/ISO string to Africa/Nairobi time and format
    function toNairobiString(dt) {
        if (!dt) return '-';
        const d = new Date(dt);
        // Africa/Nairobi is UTC+3, no DST
        const nairobiOffset = 3 * 60; // minutes
        // Get UTC time, add offset
        const local = new Date(d.getTime() + (nairobiOffset - d.getTimezoneOffset()) * 60000);
        return local.toLocaleString('en-KE', {
            month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', hour12: true
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
                        let badgeClass = 'bg-gray-200 text-gray-800';
                        if (batch.status === 'active' || batch.status === 'Active') badgeClass = 'bg-blue-200 text-blue-800';
                        if (batch.status === 'completed' || batch.status === 'Completed') badgeClass = 'bg-green-200 text-green-800';
                        if (batch.status === 'delayed' || batch.status === 'Delayed') badgeClass = 'bg-red-200 text-red-800';
                        tbody.innerHTML += `<tr>
                        <td>${batch.name}</td>
                        <td><span class='px-2 py-1 rounded ${badgeClass}'>${batch.status}</span></td>
                        <td>${toNairobiString(batch.scheduled_start)}</td>
                        <td>${toNairobiString(batch.actual_start)}</td>
                        <td>${toNairobiString(batch.actual_end)}</td>
                        <td title='${batch.notes ?? ''}'>${batch.notes ? batch.notes.substring(0, 30) + (batch.notes.length > 30 ? '...' : '') : '-'}</td>
                    </tr>`;
                    });
                }
            });
    }
    // --- Live Activity Timeline ---
    function fetchActivityTimelineLive() {
        fetch("/api/notifications-live")
            .then(res => res.json())
            .then(data => {
                const timeline = document.querySelector('.activity-timeline');
                timeline.innerHTML = '';
                if (data.notifications && data.notifications.length) {
                    data.notifications.forEach(note => {
                        timeline.innerHTML += `<li class=\"relative pb-8\">
                            <div class=\"relative flex space-x-3\">
                                <div>
                                    <span class=\"h-8 w-8 rounded-full bg-sky-400 flex items-center justify-center ring-8 ring-white\">
                                        <svg class=\"h-5 w-5 text-white\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 6v6m0 0v6m0-6h6m-6 0H6\"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class=\"min-w-0 flex-1 pt-1.5 flex justify-between space-x-4\">
                                    <div>
                                        <p class=\"text-sm text-gray-500\">${note}</p>
                                    </div>
                                    <div class=\"text-right text-sm whitespace-nowrap text-gray-500\">
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
    // --- Production Trends Chart (Last 7/30 Days) ---
    let productionTrendsChart;

    function fetchProductionTrends(days = 7) {
        fetch(`/api/production-batch-output-trends?days=${days}`)
            .then(res => res.json())
            .then(data => {
                const chartContainer = document.getElementById('productionTrendsChart').parentNode;
                chartContainer.querySelectorAll('.no-data-message, .error-message').forEach(el => el.remove());
                if (!data || data.length === 0) {
                    document.getElementById('productionTrendsChart').style.display = 'none';
                    const msg = document.createElement('div');
                    msg.className = 'text-center text-gray-400 py-8 no-data-message';
                    msg.textContent = 'No production data available for this period.';
                    chartContainer.appendChild(msg);
                    return;
                } else {
                    document.getElementById('productionTrendsChart').style.display = '';
                }
                const labels = data.map(day => day.date);
                const outputs = data.map(day => day.total_output);
                const ctx = document.getElementById('productionTrendsChart').getContext('2d');
                if (productionTrendsChart) productionTrendsChart.destroy();
                productionTrendsChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Total Output',
                            data: outputs,
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
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
            })
            .catch(err => {
                const chartContainer = document.getElementById('productionTrendsChart').parentNode;
                document.getElementById('productionTrendsChart').style.display = 'none';
                chartContainer.querySelectorAll('.no-data-message, .error-message').forEach(el => el.remove());
                const msg = document.createElement('div');
                msg.className = 'text-center text-red-500 py-8 error-message';
                msg.textContent = 'Error loading chart data.';
                chartContainer.appendChild(msg);
                console.error(err);
            });
    }
    document.getElementById('trendDaysSelect').addEventListener('change', function() {
        fetchProductionTrends(this.value);
    });
    fetchProductionTrends(7);
    // --- Download report function ---
    function downloadProductionReport() {
        window.location.href = '/api/production-report';
    }
    // --- Initial fetch and polling ---
    fetchProductionStatsLive();
    fetchRecentBatchesLive();
    fetchActivityTimelineLive();
    setInterval(fetchActivityTimelineLive, 5000);
    // setInterval(fetchProductionStatsLive, 60000); // polling disabled
    // setInterval(fetchRecentBatchesLive, 60000); // polling disabled
    // setInterval(fetchProductionActivityLive, 60000); // polling disabled

    function fetchProductionActivity() {
        fetch('/bakery/api/production-batches')
            .then(res => res.json())
            .then(batches => {
                const list = document.getElementById('production-activity-list');
                if (!batches.length) {
                    list.innerHTML = '<li class="text-gray-400">No production activity today.</li>';
                    return;
                }
                list.innerHTML = batches.map(batch => {
                    let statusColor = {
                        'planned': 'bg-gray-200 text-gray-700',
                        'active': 'bg-blue-200 text-blue-800',
                        'completed': 'bg-green-200 text-green-800',
                        'cancelled': 'bg-red-200 text-red-800'
                    }[batch.status] || 'bg-gray-100 text-gray-700';
                    let timeInfo = '';
                    if (batch.actual_start) timeInfo += `Started: <span class=\"font-medium\">${toNairobiString(batch.actual_start)}</span> `;
                    if (batch.actual_end) timeInfo += `| Completed: <span class=\"font-medium\">${toNairobiString(batch.actual_end)}</span>`;
                    return `<li class='relative pb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between'>
                        <div>
                            <span class='font-bold'>${batch.name}</span>
                            <span class='ml-2 text-sm text-gray-500'>(${batch.product ? batch.product.name : ''})</span>
                        </div>
                        <div class='flex items-center gap-3 mt-1 sm:mt-0'>
                            <span class='px-2 py-1 rounded ${statusColor} text-xs font-semibold capitalize'>${batch.status}</span>
                            <span class='text-xs text-gray-500'>${timeInfo}</span>
                        </div>
                    </li>`;
                }).join('');
            })
            .catch(() => {
                document.getElementById('production-activity-list').innerHTML = '<li class="text-red-400">Failed to load activity.</li>';
            });
    }
    fetchProductionActivity();
    setInterval(fetchProductionActivity, 10000);
</script>
@endpush