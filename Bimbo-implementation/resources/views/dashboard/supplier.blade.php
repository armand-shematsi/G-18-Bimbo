@extends('layouts.supplier')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Supplier Dashboard</h1>
        <p class="mt-1 text-sm text-gray-600">Welcome back, {{ auth()->user()->name }}! Here's your business overview.</p>
    </div>
    <div class="text-right">
        <p class="text-sm text-gray-500">Last updated</p>
        <p class="text-sm font-medium text-gray-900">{{ now()->format('M d, Y H:i') }}</p>
    </div>
</div>
@endsection

@section('navigation-links')
<a href="{{ route('supplier.inventory.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
    Raw Material Inventory
</a>
<a href="{{ route('supplier.orders.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
    Order Requests
</a>
@php
use App\Models\Message;
use App\Models\User;
use App\Models\Inventory;
use App\Models\Order;

$unreadCount = Message::where('receiver_id', auth()->id())
->where('is_read', false)
->whereIn('sender_id', User::where('role', 'retail_manager')->pluck('id'))
->count();
$unreadFromRetailer = Message::where('receiver_id', auth()->id())
->where('is_read', false)
->whereIn('sender_id', User::where('role', 'retailer')->pluck('id'))
->count();

// Get inventory statistics
$totalInventory = Inventory::where('user_id', auth()->id())->count();
$availableItems = Inventory::where('user_id', auth()->id())->where('status', 'available')->count();
$lowStockItems = Inventory::where('user_id', auth()->id())->where('status', 'low_stock')->count();
$outOfStockItems = Inventory::where('user_id', auth()->id())->where('status', 'out_of_stock')->count();

// Get recent orders
$vendorId = \App\Models\Vendor::query()->value('id');
$recentOrders = Order::where('vendor_id', $vendorId)->latest()->take(5)->get();
$pendingOrders = Order::where('vendor_id', auth()->id())->where('status', 'pending')->count();
@endphp
<a href="{{ route('supplier.chat.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
    Chat with Retail Managers & Customers
    @if($unreadCount > 0)
    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-500 text-white">{{ $unreadCount }}</span>
    @endif
</a>
@endsection

@section('content')
<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg mb-8">
    <div class="px-6 py-8">
        <div class="flex items-center justify-between">
            <div class="text-white">
                <h2 class="text-2xl font-bold mb-2">Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }}, {{ auth()->user()->name }}!</h2>
                <p class="text-blue-100">Manage your inventory, track orders, and grow your business</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-24 h-24 text-blue-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Inventory -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Inventory</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalInventory }}</p>
                <p class="text-xs text-gray-500">Items in stock</p>
            </div>
        </div>
    </div>

    <!-- Available Items -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Available</p>
                <p class="text-2xl font-bold text-gray-900">{{ $availableItems }}</p>
                <p class="text-xs text-gray-500">Ready to supply</p>
            </div>
        </div>
    </div>

    <!-- Low Stock -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Low Stock</p>
                <p class="text-2xl font-bold text-gray-900">{{ $lowStockItems }}</p>
                <p class="text-xs text-gray-500">Needs attention</p>
            </div>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                <p class="text-2xl font-bold text-gray-900">{{ $pendingOrders }}</p>
                <p class="text-xs text-gray-500">Awaiting fulfillment</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Orders -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
            </div>
            <div class="p-6">
                @if($recentOrders->count() > 0)
                <div class="space-y-4">
                    @foreach($recentOrders as $order)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Order #{{ $order->id }}</p>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' :
                                           ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
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
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-6 space-y-4">
                <a href="{{ route('supplier.inventory.create') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium">Add Inventory</p>
                        <p class="text-sm text-blue-100">Update your stock</p>
                    </div>
                </a>

                <a href="{{ route('supplier.orders.create') }}" class="flex items-center p-4 bg-gradient-to-r from-green-500 to-green-600 rounded-lg text-white hover:from-green-600 hover:to-green-700 transition-all duration-200 transform hover:scale-105">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium">New Order</p>
                        <p class="text-sm text-green-100">Create order request</p>
                    </div>
                </a>

                <a href="{{ route('supplier.chat.index') }}" class="flex items-center p-4 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg text-white hover:from-purple-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 relative">
                    @if($unreadCount > 0)
                    <span class="absolute -top-2 -right-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-red-500 text-white">{{ $unreadCount }}</span>
                    @endif
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium">Chat</p>
                        <p class="text-sm text-purple-100">Message customers</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Stock Alerts -->
        @if($lowStockItems > 0 || $outOfStockItems > 0)
        <div class="mt-6 bg-white rounded-xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Stock Alerts</h3>
            </div>
            <div class="p-6">
                @if($lowStockItems > 0)
                <div class="flex items-center p-3 bg-yellow-50 rounded-lg mb-3">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-800">{{ $lowStockItems }} items low on stock</p>
                        <a href="{{ route('supplier.inventory.index') }}" class="text-xs text-yellow-600 hover:text-yellow-800">View details →</a>
                    </div>
                </div>
                @endif

                @if($outOfStockItems > 0)
                <div class="flex items-center p-3 bg-red-50 rounded-lg">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ $outOfStockItems }} items out of stock</p>
                        <a href="{{ route('supplier.inventory.index') }}" class="text-xs text-red-600 hover:text-red-800">View details →</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Activity Timeline -->
<div class="mt-8 bg-white rounded-xl shadow-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
    </div>
    <div class="p-6">
        <div class="flow-root">
            <ul class="-mb-8">
                <li class="relative pb-8">
                    <div class="relative flex space-x-3">
                        <div>
                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                            <div>
                                <p class="text-sm text-gray-500">Dashboard accessed</p>
                            </div>
                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                <time>{{ now()->format('M d, H:i') }}</time>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Supplier Orders Section -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <h2 class="text-xl font-bold text-purple-700 mb-4">Orders To You</h2>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Product</th>
                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Placed At</th>
                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            {{-- For testing, show orders for vendor_id = 1 --}}
            @if($errors->any())
                <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @foreach(\App\Models\Order::where('vendor_id', 1)->orderBy('created_at', 'desc')->get() as $order)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->items->first()->product->name ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->items->first()->quantity ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $order->status === 'shipped' ? 'text-green-700 font-bold' : ($order->status === 'delivered' ? 'text-blue-700 font-bold' : 'text-yellow-700 font-bold') }}">{{ ucfirst($order->status) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y H:i') }}</td>
                <td>
                    <form method="POST" action="{{ route('supplier.orders.updateStatus', $order) }}">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="border rounded px-2 py-1 text-xs">
                            <option value="pending" @if($order->status == 'pending') selected @endif>Pending</option>
                            <option value="processing" @if($order->status == 'processing') selected @endif>Processing</option>
                            <option value="shipped" @if($order->status == 'shipped') selected @endif>Shipped</option>
                            <option value="delivered" @if($order->status == 'delivered') selected @endif>Delivered</option>
                            <option value="cancelled" @if($order->status == 'cancelled') selected @endif>Cancelled</option>
                        </select>
                        <button type="submit" class="ml-2 bg-blue-500 text-white px-2 py-1 rounded text-xs">Update</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
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
