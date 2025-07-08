@extends('layouts.dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Admin Dashboard
    </h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
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
