@extends('layouts.dashboard')

@section('header')
    <div class="relative bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-8 py-12 sm:py-16 sm:px-16 flex flex-col sm:flex-row items-center justify-between">
            <div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-2 drop-shadow-lg">Welcome, Admin!</h2>
                <p class="text-white text-lg opacity-90">Here's a snapshot of your business performance today.</p>
            </div>
            <div class="mt-6 sm:mt-0 animate-bounce">
                <svg class="w-20 h-20 text-white opacity-80" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 48 48"><circle cx="24" cy="24" r="22" stroke="white" stroke-width="3" fill="url(#grad1)"/><path d="M16 32l8-8 8 8" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><defs><linearGradient id="grad1" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#6366f1"/><stop offset="100%" stop-color="#a21caf"/></linearGradient></defs></svg>
            </div>
        </div>
    </div>
@endsection

@section('navigation-links')
    <a href="{{ route('admin.vendors.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Manage Vendors
    </a>
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Order Management
    </a>
    <a href="{{ route('admin.analytics') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Analytics
    </a>
    <a href="/admin/analytics/inventory" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-purple-700 hover:text-purple-900 hover:border-purple-300 focus:outline-none focus:text-purple-900 focus:border-purple-300 transition">
        Inventory Analytics
    </a>
    <a href="{{ route('admin.settings') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        System Settings
    </a>
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Manage Users
    </a>
    <a href="{{ route('customer-segments.import.form') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-blue-600 hover:text-blue-700 hover:border-blue-300 focus:outline-none focus:border-blue-600 transition">
        <svg class="w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        Import Customer Segments
    </a>
@endsection

@section('content')
    <!-- Glassmorphism Cards Grid -->
    <div class="max-w-7xl mx-auto px-4 pb-12">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-bold text-gray-800">Orders Overview</h3>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">View All Orders</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
            <div class="bg-white/60 backdrop-blur-lg shadow-2xl rounded-2xl p-8 flex flex-col items-center hover:scale-105 transition-transform duration-300">
                <div class="bg-blue-200/70 rounded-full p-4 mb-4 animate-pulse">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/></svg>
                </div>
                <div class="text-gray-500 text-base">Total Bread Produced</div>
                <div class="text-3xl font-extrabold text-blue-700 mt-2">{{ $totalBreadProduced }}</div>
            </div>
            <div class="bg-white/60 backdrop-blur-lg shadow-2xl rounded-2xl p-8 flex flex-col items-center hover:scale-105 transition-transform duration-300">
                <div class="bg-green-200/70 rounded-full p-4 mb-4 animate-pulse">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 0 1 4-4h4"/></svg>
                </div>
                <div class="text-gray-500 text-base">Total Deliveries</div>
                <div class="text-3xl font-extrabold text-green-700 mt-2">{{ $totalDeliveries }}</div>
            </div>
            <div class="bg-white/60 backdrop-blur-lg shadow-2xl rounded-2xl p-8 flex flex-col items-center hover:scale-105 transition-transform duration-300">
                <div class="bg-yellow-200/70 rounded-full p-4 mb-4 animate-pulse">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/></svg>
                </div>
                <div class="text-gray-500 text-base">Pending Orders</div>
                <div class="text-3xl font-extrabold text-yellow-700 mt-2">{{ $pendingOrders }}</div>
            </div>
            <div class="bg-white/60 backdrop-blur-lg shadow-2xl rounded-2xl p-8 flex flex-col items-center hover:scale-105 transition-transform duration-300">
                <div class="bg-indigo-200/70 rounded-full p-4 mb-4 animate-pulse">
                    <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 17v-2a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v2"/></svg>
                </div>
                <div class="text-gray-500 text-base">Stock Levels</div>
                <div class="text-3xl font-extrabold text-indigo-700 mt-2">{{ $stockLevels }}</div>
            </div>
            <div class="bg-white/60 backdrop-blur-lg shadow-2xl rounded-2xl p-8 flex flex-col items-center hover:scale-105 transition-transform duration-300">
                <div class="bg-emerald-200/70 rounded-full p-4 mb-4 animate-pulse">
                    <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 0V4m0 16v-4"/></svg>
                </div>
                <div class="text-gray-500 text-base">Total Revenue</div>
                <div class="text-3xl font-extrabold text-emerald-700 mt-2">₦{{ number_format($totalRevenue, 2) }}</div>
            </div>
            <div class="bg-white/60 backdrop-blur-lg shadow-2xl rounded-2xl p-8 flex flex-col items-center hover:scale-105 transition-transform duration-300">
                <div class="bg-red-200/70 rounded-full p-4 mb-4 animate-pulse">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/></svg>
                </div>
                <div class="text-gray-500 text-base">Reorder Alerts</div>
                <div class="text-3xl font-extrabold text-red-700 mt-2">{{ $reorderAlerts }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Active Vendors -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Vendors</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $activeVendorsCount }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>


        </div>
    </div>

{{-- Reports Button at the bottom --}}
<div class="w-full flex justify-center mt-8 mb-4">
    <a href="{{ route('reports.downloads') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg text-xl transition-all duration-200">
        <i class="fas fa-file-alt mr-2"></i> Reports
    </a>
</div>
@endsection
