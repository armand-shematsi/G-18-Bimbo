@extends('layouts.dashboard')

@section('header')
    Retail Manager Dashboard
@endsection

@section('navigation-links')
    <a href="{{ route('retail.orders.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Bread Orders
    </a>
    <a href="{{ route('retail.inventory.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Inventory Levels
    </a>
    <a href="{{ route('retail.forecast.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Demand Forecast
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Current Inventory -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Current Inventory</dt>
                            <dd class="text-lg font-medium text-gray-900">0 Units</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending Orders</dt>
                            <dd class="text-lg font-medium text-gray-900">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Items -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Low Stock Items</dt>
                            <dd class="text-lg font-medium text-gray-900">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="mt-8">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Orders</h3>
        <div class="mt-4 bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                <li class="px-6 py-4">
                    <p class="text-sm text-gray-500">No recent orders</p>
                </li>
            </ul>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Actions</h3>
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <a href="{{ route('retail.orders.create') }}" class="relative block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
                <h4 class="text-lg font-medium text-gray-900">Place Order</h4>
                <p class="mt-1 text-sm text-gray-500">Order new bread products</p>
            </a>
            <a href="{{ route('retail.inventory.check') }}" class="relative block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
                <h4 class="text-lg font-medium text-gray-900">Check Inventory</h4>
                <p class="mt-1 text-sm text-gray-500">View current stock levels</p>
            </a>
            <a href="{{ route('retail.forecast.index') }}" class="relative block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
                <h4 class="text-lg font-medium text-gray-900">View Forecast</h4>
                <p class="mt-1 text-sm text-gray-500">Check demand predictions</p>
            </a>
        </div>
    </div>
@endsection 