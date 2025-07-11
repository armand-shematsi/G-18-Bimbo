@php
use App\Models\Message;
use App\Models\User;
$unreadCount = Message::where('receiver_id', auth()->id())
    ->where('is_read', false)
    ->whereIn('sender_id', User::where('role', 'retail_manager')->pluck('id'))
    ->count();
// Add default values for dashboard variables
$totalInventory = $totalInventory ?? 0;
$availableItems = $availableItems ?? 0;
$lowStockItems = $lowStockItems ?? 0;
$pendingOrders = $pendingOrders ?? 0;
$recentOrders = $recentOrders ?? collect();
@endphp
@extends('layouts.supplier')

@section('header')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center space-x-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Supplier Dashboard</h1>
            <p class="text-sm text-gray-500">Business Overview & Performance</p>
        </div>
    </div>
    <div class="text-right">
        <p class="text-xs text-gray-400">Last updated</p>
        <p class="text-sm font-medium text-gray-700">{{ now()->format('M d, Y H:i') }}</p>
    </div>
</div>
@endsection

@section('navigation-links')
<a href="{{ route('supplier.inventory.index') }}" class="inline-flex items-center px-2 py-1 rounded hover:bg-blue-50 text-sm font-medium text-gray-700 transition">Inventory</a>
<a href="{{ route('supplier.orders.index') }}" class="inline-flex items-center px-2 py-1 rounded hover:bg-blue-50 text-sm font-medium text-gray-700 transition">Orders</a>
<a href="{{ route('supplier.chat.index') }}" class="inline-flex items-center px-2 py-1 rounded hover:bg-blue-50 text-sm font-medium text-gray-700 transition relative">
    Chat
    @if($unreadCount > 0)
    <span class="ml-2 absolute -top-2 -right-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-500 text-white">{{ $unreadCount }}</span>
    @endif
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
    <!-- Business Summary -->
    <div class="md:col-span-2 bg-white rounded-xl shadow p-6 border border-gray-100">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Business Summary</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="flex flex-col items-start">
                <span class="text-xs text-gray-500 mb-1">Total Inventory</span>
                <span class="text-2xl font-bold text-blue-700">{{ $totalInventory }}</span>
                <span class="text-xs text-gray-400">Items in stock</span>
            </div>
            <div class="flex flex-col items-start">
                <span class="text-xs text-gray-500 mb-1">Available</span>
                <span class="text-2xl font-bold text-green-600">{{ $availableItems }}</span>
                <span class="text-xs text-gray-400">Ready to supply</span>
            </div>
            <div class="flex flex-col items-start">
                <span class="text-xs text-gray-500 mb-1">Low Stock</span>
                <span class="text-2xl font-bold text-yellow-600">{{ $lowStockItems }}</span>
                <span class="text-xs text-gray-400">Needs attention</span>
            </div>
            <div class="flex flex-col items-start">
                <span class="text-xs text-gray-500 mb-1">Pending Orders</span>
                <span class="text-2xl font-bold text-purple-700">{{ $pendingOrders }}</span>
                <span class="text-xs text-gray-400">Awaiting fulfillment</span>
            </div>
        </div>
    </div>
    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow p-6 border border-gray-100 flex flex-col justify-between">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
        <a href="{{ route('supplier.orders.create') }}" class="mb-3 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 transition font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            New Order
        </a>
        <a href="{{ route('supplier.inventory.index') }}" class="mb-3 inline-flex items-center px-4 py-2 bg-green-600 text-white rounded shadow hover:bg-green-700 transition font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" /></svg>
            Manage Inventory
        </a>
        <a href="{{ route('supplier.inventory.index') }}" class="mb-3 inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded shadow hover:bg-purple-700 transition font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
            View All Inventory
        </a>
        <a href="{{ route('supplier.chat.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-700 text-white rounded shadow hover:bg-gray-800 transition font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2V10a2 2 0 012-2h2" /></svg>
            Open Chat
        </a>
    </div>
</div>

<!-- Recent Orders -->
<div class="bg-white rounded-xl shadow p-6 border border-gray-100 mb-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-800">Recent Orders</h2>
        <a href="{{ route('supplier.orders.index') }}" class="text-blue-600 hover:underline text-sm">View All</a>
    </div>
    @if($recentOrders->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach($recentOrders as $order)
                <tr>
                    <td class="px-4 py-2 font-semibold text-gray-800">#{{ $order->id }}</td>
                    <td class="px-4 py-2 text-gray-600">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' :
                               ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-gray-700">${{ number_format($order->total, 2) }}</td>
                    <td class="px-4 py-2 text-right">
                        <a href="{{ route('supplier.orders.show', $order) }}" class="text-blue-600 hover:underline text-sm">Details</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-8">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
        <p class="mt-2 text-sm text-gray-500">No recent orders</p>
    </div>
    @endif
</div>
<div class="card my-4 shadow-sm" style="border: 2px solid #007bff; background: linear-gradient(90deg, #e3f2fd 0%, #fff 100%);">
    <div class="card-body">
        <h4 class="card-title mb-3" style="font-size: 2rem; font-weight: bold; color: #007bff;">
            <i class="fas fa-file-alt" style="font-size: 2.2rem; color: #007bff; vertical-align: middle;"></i>
            Reports Center
        </h4>
        <div class="d-flex flex-wrap gap-3">
            <a href="{{ route('reports.downloads') }}" class="btn btn-info btn-lg d-flex align-items-center" style="font-size: 1.3rem; font-weight: bold; background: #17a2b8; border: none; border-radius: 6px;">
                <i class="fas fa-eye mr-2" style="font-size: 2rem; color: #fff;"></i> View Your Reports
            </a>
        </div>
        <div class="d-flex flex-wrap gap-3 mt-3">
            <a href="{{ route('reports.downloads') }}" class="btn btn-success btn-lg d-flex align-items-center" style="font-size: 1.3rem; font-weight: bold; background: #28a745; border: none; border-radius: 6px;">
                <i class="fas fa-file-download mr-2" style="font-size: 2rem; color: #fff;"></i> Download Your Reports
            </a>
        </div>
        <p class="text-muted mt-3 mb-0" style="font-size: 1.1rem; font-weight: 500; color: #333 !important;">Access all your daily and weekly reports in one place.</p>
    </div>
</div>
@endsection
