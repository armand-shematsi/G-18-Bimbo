@extends('layouts.dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Admin Dashboard
    </h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-end gap-4">
            <a href="{{ route('admin.analytics') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-lg px-8 py-3 rounded-xl shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 4h6a2 2 0 002-2v-6a2 2 0 00-2-2h-2a2 2 0 00-2 2v6a2 2 0 002 2z" /></svg>
                Go to Analytics Dashboard
            </a>
        </div>
        <div class="px-4 py-6 sm:px-0 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900">Total Bread Produced</h3>
                <p class="mt-2 text-2xl font-bold">{{ $totalBreadProduced }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900">Total Deliveries</h3>
                <p class="mt-2 text-2xl font-bold">{{ $totalDeliveries }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900">Pending Orders</h3>
                <p class="mt-2 text-2xl font-bold">{{ $pendingOrders }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900">Stock Levels</h3>
                <p class="mt-2 text-2xl font-bold">{{ $stockLevels }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900">Total Revenue</h3>
                <p class="mt-2 text-2xl font-bold">₦{{ number_format($totalRevenue, 2) }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900">Reorder Alerts</h3>
                <p class="mt-2 text-2xl font-bold text-red-600">{{ $reorderAlerts }}</p>
            </div>
        </div>
    </div>
@endsection
