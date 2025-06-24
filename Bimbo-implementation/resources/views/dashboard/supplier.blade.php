@extends('layouts.supplier')

@section('header')
    Supplier Dashboard
@endsection

@section('navigation-links')
    <a href="{{ route('supplier.inventory.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Raw Material Inventory
    </a>
    <a href="{{ route('supplier.orders') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Order Requests
    </a>
    @php
    use App\Models\Message;
    use App\Models\User;
    $unreadCount = Message::where('receiver_id', auth()->id())
        ->where('is_read', false)
        ->whereIn('sender_id', User::where('role', 'retail_manager')->pluck('id'))
        ->count();
    $unreadFromRetailer = Message::where('receiver_id', auth()->id())
        ->where('is_read', false)
        ->whereIn('sender_id', User::where('role', 'retailer')->pluck('id'))
        ->count();
    @endphp
    <div class="p-2 text-xs text-red-600">DEBUG: Unread messages (retail_manager) = {{ $unreadCount }}</div>
    <div class="p-2 text-xs text-blue-600">DEBUG: Unread messages (retailer) = {{ $unreadFromRetailer }}</div>
    <a href="{{ route('supplier.chat.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Chat with Retail Managers
        @if($unreadCount > 0)
            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-500 text-white">{{ $unreadCount }}</span>
        @endif
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Inventory Status -->
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
                            <dd class="text-lg font-medium text-gray-900">0 Items</dd>
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

        <!-- Unread Messages -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Unread Messages</dt>
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
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <a href="{{ route('supplier.inventory.add') }}" class="relative block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
                <h4 class="text-lg font-medium text-gray-900">Add New Inventory</h4>
                <p class="mt-1 text-sm text-gray-500">Update your raw material inventory</p>
            </a>
            <a href="{{ route('supplier.orders.new') }}" class="relative block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
                <h4 class="text-lg font-medium text-gray-900">Create New Order</h4>
                <p class="mt-1 text-sm text-gray-500">Submit a new order request</p>
            </a>
        </div>
    </div>
@endsection 