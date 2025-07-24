@extends('layouts.dashboard')

@section('header')
    <div class="flex items-center gap-4 mb-6">
        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=4f46e5&color=fff&size=80" class="h-16 w-16 rounded-full shadow-lg border-4 border-indigo-200" alt="Admin Avatar">
        <div>
            <h2 class="font-extrabold text-3xl text-indigo-800 leading-tight">Welcome back, {{ Auth::user()->name ?? 'Admin' }}!</h2>
            <p class="text-gray-500 text-lg">Here’s your latest bakery performance at a glance.</p>
        </div>
    </div>
@endsection

@section('content')
<div class="relative max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
    <!-- Soft SVG Background -->
    <svg class="absolute -top-20 -right-32 w-[600px] h-[600px] opacity-10 pointer-events-none select-none" viewBox="0 0 600 600" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="300" cy="300" r="300" fill="url(#paint0_radial)" />
        <defs>
            <radialGradient id="paint0_radial" cx="0" cy="0" r="1" gradientTransform="translate(300 300) scale(300)" gradientUnits="userSpaceOnUse">
                <stop stop-color="#6366f1" />
                <stop offset="1" stop-color="#a5b4fc" stop-opacity="0" />
            </radialGradient>
        </defs>
    </svg>
    <!-- Card Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-10">
        <div class="glass-card group">
            <div class="icon-badge bg-blue-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <div class="text-4xl font-extrabold text-indigo-900 drop-shadow">{{ $totalBreadProduced }}</div>
                <div class="font-semibold text-gray-700">Total Bread Produced</div>
                <span class="trend-badge up">+5% this week</span>
            </div>
        </div>
        <div class="glass-card group">
            <div class="icon-badge bg-green-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 4h6a2 2 0 002-2v-6a2 2 0 00-2-2h-2a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div class="text-4xl font-extrabold text-indigo-900 drop-shadow">{{ $totalDeliveries }}</div>
                <div class="font-semibold text-gray-700">Total Deliveries</div>
                <span class="trend-badge down">-2% this week</span>
            </div>
        </div>
        <div class="glass-card group">
            <div class="icon-badge bg-yellow-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <div class="text-4xl font-extrabold text-indigo-900 drop-shadow">{{ $pendingOrders }}</div>
                <div class="font-semibold text-gray-700">Pending Orders</div>
                <span class="status-badge bg-yellow-100 text-yellow-800">Pending</span>
            </div>
        </div>
        <div class="glass-card group">
            <div class="icon-badge bg-purple-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/></svg>
            </div>
            <div>
                <div class="text-4xl font-extrabold text-indigo-900 drop-shadow">{{ $stockLevels }}</div>
                <div class="font-semibold text-gray-700">Stock Levels</div>
                <span class="status-badge bg-green-100 text-green-800">Healthy</span>
            </div>
        </div>
        <div class="glass-card group">
            <div class="icon-badge bg-pink-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/></svg>
            </div>
            <div>
                <div class="text-4xl font-extrabold flex items-baseline">
                    <span class="text-3xl mr-1">₦</span>
                    <span>{{ number_format($totalRevenue, 2) }}</span>
                </div>
                <div class="font-semibold">Total Revenue</div>
                <span class="trend-badge up">+12% this month</span>
            </div>
        </div>
        <div class="glass-card group">
            <div class="icon-badge bg-red-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <div class="text-4xl font-extrabold text-indigo-900 drop-shadow">{{ $reorderAlerts }}</div>
                <div class="font-semibold text-gray-700">Reorder Alerts</div>
                <span class="status-badge bg-red-100 text-red-800">Critical</span>
            </div>
        </div>
    </div>
    <!-- Mini Sales Trend Chart & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <div class="bg-white/80 rounded-2xl shadow-xl p-8 flex flex-col justify-between">
            <h3 class="text-xl font-bold text-indigo-700 mb-4">Sales Trend (Last 7 Days)</h3>
            <canvas id="salesTrendChart" height="120"></canvas>
        </div>
        <div class="bg-white/80 rounded-2xl shadow-xl p-8">
            <h3 class="text-xl font-bold text-indigo-700 mb-4">Recent Activity</h3>
            <ul class="divide-y divide-indigo-100">
                <li class="py-3 flex items-center gap-3"><span class="status-badge bg-green-100 text-green-800">Order</span> New order placed by <span class="font-semibold">Retailer A</span></li>
                <li class="py-3 flex items-center gap-3"><span class="status-badge bg-blue-100 text-blue-800">User</span> New user <span class="font-semibold">John Doe</span> registered</li>
                <li class="py-3 flex items-center gap-3"><span class="status-badge bg-yellow-100 text-yellow-800">Stock</span> Low stock alert for <span class="font-semibold">Flour</span></li>
                <li class="py-3 flex items-center gap-3"><span class="status-badge bg-purple-100 text-purple-800">Report</span> Weekly report generated</li>
            </ul>
        </div>
    </div>
    <!-- Quick Actions -->
    <div class="mb-10">
        <h3 class="text-xl font-bold text-indigo-700 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.users.index') }}" class="quick-action-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Manage Users
            </a>
            <a href="{{ route('admin.analytics') }}" class="quick-action-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 4h6a2 2 0 002-2v-6a2 2 0 00-2-2h-2a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                View Analytics
            </a>
            <a href="{{ route('reports.index') }}" class="quick-action-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                View Reports
            </a>
            <a href="{{ route('admin.vendors.index') }}" class="quick-action-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M17 8a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Manage Vendors
            </a>
        </div>
    </div>
    <!-- Chart.js for Sales Trend -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesTrendChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Sales',
                        data: [120, 150, 170, 140, 180, 200, 220],
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    </script>
    <style>
        .glass-card {
            background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px 0 rgba(59, 130, 246, 0.10);
            border: 1.5px solid #93c5fd;
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: box-shadow 0.2s, transform 0.2s;
            position: relative;
            overflow: hidden;
        }
        .glass-card:hover {
            box-shadow: 0 12px 36px 0 rgba(59, 130, 246, 0.18);
            transform: translateY(-2px) scale(1.03);
        }
        .icon-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            padding: 0.9rem;
            background: linear-gradient(135deg, #6366f1 0%, #3b82f6 100%);
            color: #fff;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.10);
        }
        .glass-card .text-4xl {
            color: #1e293b;
            font-weight: 800;
        }
        .glass-card .font-semibold {
            color: #475569;
        }
        .trend-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.95em;
            font-weight: 600;
            border-radius: 999px;
            padding: 0.2em 0.8em;
            margin-top: 0.5em;
        }
        .trend-badge.up { background: #d1fae5; color: #047857; }
        .trend-badge.down { background: #fee2e2; color: #b91c1c; }
        .status-badge {
            display: inline-block;
            padding: 0.25em 0.7em;
            border-radius: 999px;
            font-size: 0.95em;
            font-weight: 600;
            margin-top: 0.5em;
        }
        .quick-action-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(90deg, #3b82f6 0%, #6366f1 100%);
            color: #fff;
            font-weight: 700;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.08);
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .quick-action-btn:hover {
            box-shadow: 0 8px 32px 0 rgba(99, 102, 241, 0.18);
            transform: scale(1.04);
            color: #fff;
        }
    </style>
</div>
@endsection
