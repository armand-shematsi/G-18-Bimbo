@extends('layouts.supplier')

@section('header')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-blue-900">Order Management</h1>
        <p class="mt-1 text-sm text-gray-600">Manage and track all your order requests from customers.</p>
    </div>
    <div class="flex space-x-3">
        @can('order-as-bakery')
        <a href="{{ route('supplier.orders.create') }}" class="add-btn inline-flex items-center px-6 py-2 text-base rounded-xl shadow font-semibold uppercase tracking-wide">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            New Order
        </a>
        @endcan
    </div>
</div>
@endsection

@section('navigation-links')
<a href="{{ route('supplier.inventory.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
    Raw Material Inventory
</a>
<a href="{{ route('supplier.orders.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:text-gray-700 focus:border-gray-300">
    Order Requests
</a>
@php
use App\Models\Message;
use App\Models\User;

$unreadCount = Message::where('receiver_id', auth()->id())
->where('is_read', false)
->whereIn('sender_id', User::where('role', 'retail_manager')->pluck('id'))
->count();
@endphp
<a href="{{ route('supplier.chat.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
    Chat with Retail Managers & Customers
    @if($unreadCount > 0)
    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-500 text-white">{{ $unreadCount }}</span>
    @endif
</a>
@endsection

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #e0f2fe 0%, #f1f5f9 100%);
    }
    .add-btn {
        background: linear-gradient(90deg, #38bdf8 0%, #6366f1 100%);
        color: #fff;
        font-weight: bold;
        border-radius: 0.75rem;
        box-shadow: 0 2px 8px rgba(59,130,246,0.10);
        transition: background 0.2s, transform 0.2s;
    }
    .add-btn:hover {
        background: linear-gradient(90deg, #6366f1 0%, #38bdf8 100%);
        transform: scale(1.04);
    }
    .glass-card {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 100%);
        border-radius: 1.5rem;
        box-shadow: 0 4px 24px 0 rgba(59, 130, 246, 0.10);
        border: 1.5px solid #bae6fd;
        padding: 2rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: box-shadow 0.2s, transform 0.2s;
        position: relative;
        overflow: hidden;
    }
    .glass-card .icon-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        padding: 0.9rem;
        background: linear-gradient(135deg, #38bdf8 0%, #6366f1 100%);
        color: #fff;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.10);
    }
    .glass-table {
        border-radius: 1.25rem;
        overflow: hidden;
        border: 1.5px solid #bae6fd;
        box-shadow: 0 2px 12px 0 rgba(59,130,246,0.08);
        background: #fff;
        width: 100%;
    }
    .glass-table th {
        position: sticky;
        top: 0;
        background: linear-gradient(90deg, #e0e7ff 0%, #f0f9ff 100%);
        z-index: 1;
        font-weight: 800;
        color: #3730a3;
        box-shadow: 0 2px 8px rgba(59,130,246,0.04);
        padding-top: 1rem;
        padding-bottom: 1rem;
        font-size: 0.95rem;
        letter-spacing: 0.05em;
    }
    .glass-table td {
        padding-top: 1rem;
        padding-bottom: 1rem;
        font-size: 1rem;
        font-weight: 500;
        transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
    }
    .glass-table tbody tr:nth-child(even) {
        background: #f8fafc;
    }
    .glass-table tbody tr:nth-child(odd) {
        background: #fff;
    }
    .glass-table tr:hover {
        background: #e0e7ff !important;
        box-shadow: 0 4px 16px 0 rgba(99,102,241,0.10);
        transform: scale(1.01);
    }
    .empty-state {
        text-align: center;
        padding: 3rem 0;
        color: #64748b;
    }
    .empty-state svg {
        margin: 0 auto 1rem auto;
        display: block;
    }
    .empty-state h3 {
        font-size: 1.2rem;
        font-weight: bold;
        color: #1e293b;
    }
</style>
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Orders -->
    <div class="glass-card">
        <div class="icon-badge bg-blue-400">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Total Orders</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $totalOrders }}</p>
            <p class="text-xs text-gray-500">All time</p>
        </div>
    </div>
    <!-- Pending Orders -->
    <div class="glass-card">
        <div class="icon-badge bg-yellow-400">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Pending</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $pendingOrders }}</p>
            <p class="text-xs text-gray-500">Awaiting action</p>
        </div>
    </div>
    <!-- Processing Orders -->
    <div class="glass-card">
        <div class="icon-badge bg-orange-400">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Processing</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $processingOrders }}</p>
            <p class="text-xs text-gray-500">In progress</p>
        </div>
    </div>
    <!-- Completed Orders -->
    <div class="glass-card">
        <div class="icon-badge bg-green-400">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Completed</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $completedOrders }}</p>
            <p class="text-xs text-gray-500">Delivered/Shipped</p>
        </div>
    </div>
</div>
<!-- Orders Table -->
<div class="glass-table overflow-x-auto">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
            <div class="flex space-x-2">
                <select id="status-filter" class="text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
    </div>
    <div>
        @if($orders->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                        <div class="text-sm text-gray-500">{{ $order->items->count() }} items</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $order->customer_name ?? $order->user->name ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-500">{{ $order->user->email ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                       ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' :
                                       ($order->status === 'shipped' ? 'bg-purple-100 text-purple-800' :
                                       ($order->status === 'delivered' ? 'bg-green-100 text-green-800' :
                                       'bg-red-100 text-red-800'))) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${{ number_format($order->total ?? 0, 2) }}</div>
                        <div class="text-sm text-gray-500">
                            {{ $order->payment_status ?? 'Pending' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</div>
                        <div class="text-sm text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('supplier.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            @if($order->status === 'pending')
                            <form action="{{ route('supplier.orders.updateStatus', $order) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="processing">
                                <button type="submit" class="text-green-600 hover:text-green-900">Process</button>
                            </form>
                            @endif
                            @if($order->status === 'processing')
                            <form action="{{ route('supplier.orders.updateStatus', $order) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="shipped">
                                <button type="submit" class="text-purple-600 hover:text-purple-900">Ship</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{-- {{ $orders->links() }} --}}
        </div>
        @else
        <div class="empty-state">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="mt-2 text-base font-bold text-gray-900">No orders</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new order request.</p>
            @can('order-as-bakery')
            <div class="mt-6">
                <a href="{{ route('supplier.orders.create') }}" class="add-btn inline-flex items-center px-6 py-2 text-base rounded-xl shadow font-semibold uppercase tracking-wide">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New Order
                </a>
            </div>
            @endcan
        </div>
        @endif
    </div>
</div>

<!-- Success Message -->
@if(session('success'))
<div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg" id="success-message">
    {{ session('success') }}
</div>
<script>
    setTimeout(function() {
        document.getElementById('success-message').style.display = 'none';
    }, 3000);
</script>
@endif
@endsection

@push('scripts')
<script>
    // Status filter functionality
    document.getElementById('status-filter').addEventListener('change', function() {
        const status = this.value;
        const currentUrl = new URL(window.location);

        if (status) {
            currentUrl.searchParams.set('status', status);
        } else {
            currentUrl.searchParams.delete('status');
        }

        window.location.href = currentUrl.toString();
    });

    // Set the current filter value
    const urlParams = new URLSearchParams(window.location.search);
    const currentStatus = urlParams.get('status');
    if (currentStatus) {
        document.getElementById('status-filter').value = currentStatus;
    }
</script>
@endpush
