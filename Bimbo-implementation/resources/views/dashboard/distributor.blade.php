@extends('layouts.distributor')

@section('header')
    Distribution Team Dashboard
@endsection

@section('navigation-links')
    <a href="{{ route('distributor.routes') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Route Optimization
    </a>
    <a href="{{ route('distributor.vehicles') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Vehicle Tracking
    </a>
    <a href="{{ route('distributor.deliveries') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Delivery Management
    </a>
    <a href="{{ route('distributor.orders.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Order Management
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Active Deliveries -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Deliveries</dt>
                            <dd class="text-lg font-medium text-gray-900">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Vehicles -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Vehicles</dt>
                            <dd class="text-lg font-medium text-gray-900">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Confirmations -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending Confirmations</dt>
                            <dd class="text-lg font-medium text-gray-900">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Routes -->
    <div class="mt-8">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Today's Delivery Routes</h3>
        <div class="mt-4 bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                <li class="px-6 py-4">
                    <p class="text-sm text-gray-500">No routes scheduled for today</p>
                </li>
            </ul>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Actions</h3>
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <a href="{{ route('distributor.routes.create') }}" class="relative block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
                <h4 class="text-lg font-medium text-gray-900">Create Route</h4>
                <p class="mt-1 text-sm text-gray-500">Plan a new delivery route</p>
            </a>
            <a href="{{ route('distributor.vehicles.assign') }}" class="relative block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
                <h4 class="text-lg font-medium text-gray-900">Assign Vehicle</h4>
                <p class="mt-1 text-sm text-gray-500">Assign vehicle to route</p>
            </a>
            <a href="{{ route('distributor.deliveries.confirm') }}" class="relative block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
                <h4 class="text-lg font-medium text-gray-900">Confirm Delivery</h4>
                <p class="mt-1 text-sm text-gray-500">Mark deliveries as complete</p>
            </a>
        </div>
    </div>
@endsection 