@extends('layouts.dashboard')

@section('header')
    <h1 class="text-3xl font-bold text-gray-900">Order Analytics</h1>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto py-8">
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Order Trends (Last 30 Days)</h2>
            <canvas id="ordersTrendsChart" height="100"></canvas>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Order Status Distribution</h2>
                <canvas id="statusDistributionChart" height="100"></canvas>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Monthly Revenue (Last 12 Months)</h2>
                <canvas id="monthlyRevenueChart" height="100"></canvas>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Top Vendors by Revenue</h2>
            <canvas id="vendorPerformanceChart" height="100"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data from controller
    const dailyOrders = @json($dailyOrders);
    const statusDistribution = @json($statusDistribution);
    const vendorPerformance = @json($vendorPerformance);
    const monthlyRevenue = @json($monthlyRevenue);

    // Order Trends Chart
    const ordersTrendsCtx = document.getElementById('ordersTrendsChart').getContext('2d');
    new Chart(ordersTrendsCtx, {
        type: 'line',
        data: {
            labels: dailyOrders.map(d => d.date),
            datasets: [{
                label: 'Orders',
                data: dailyOrders.map(d => d.count),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // Order Status Distribution Chart
    const statusDistCtx = document.getElementById('statusDistributionChart').getContext('2d');
    new Chart(statusDistCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusDistribution),
            datasets: [{
                label: 'Orders',
                data: Object.values(statusDistribution),
                backgroundColor: [
                    '#fbbf24', // pending
                    '#3b82f6', // processing
                    '#10b981', // delivered
                    '#ef4444', // cancelled
                    '#6366f1', // shipped/other
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Monthly Revenue Chart
    const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(monthlyRevenueCtx, {
        type: 'bar',
        data: {
            labels: monthlyRevenue.map(m => m.month),
            datasets: [{
                label: 'Revenue',
                data: monthlyRevenue.map(m => m.revenue),
                backgroundColor: '#6366f1'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // Vendor Performance Chart
    const vendorPerfCtx = document.getElementById('vendorPerformanceChart').getContext('2d');
    new Chart(vendorPerfCtx, {
        type: 'bar',
        data: {
            labels: vendorPerformance.map(v => v.vendor ? v.vendor.name : 'Unknown'),
            datasets: [{
                label: 'Revenue',
                data: vendorPerformance.map(v => v.total_revenue),
                backgroundColor: '#10b981'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });
</script>
@endpush 