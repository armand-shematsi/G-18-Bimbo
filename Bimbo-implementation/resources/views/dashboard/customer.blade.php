@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Customer Dashboard</h1>
            <p class="mt-0.5 text-xs text-gray-600">Welcome back, {{ Auth::user()->name }}! Here's your activity overview.</p>
        </div>
        <div class="text-right">
            <p class="text-xs text-gray-500">Last updated</p>
            <p class="text-xs font-medium text-gray-900">{{ now()->format('M d, Y H:i') }}</p>
        </div>
    </div>
@endsection

@section('content')
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-green-500 to-blue-500 rounded-lg shadow mb-4">
        <div class="px-4 py-4 flex items-center justify-between">
            <div class="text-white">
                <h2 class="text-lg font-bold mb-1">Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }}, {{ Auth::user()->name }}!</h2>
                <p class="text-blue-100 text-xs">Track your orders, check delivery status, and chat with suppliers.</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-14 h-14 text-blue-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Orders -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-600">Total Orders</p>
                    <p class="text-lg font-bold text-gray-900">{{ $recentOrders ? $recentOrders->count() : 0 }}</p>
                    <p class="text-[10px] text-gray-500">Placed by you</p>
                </div>
            </div>
        </div>
        <!-- Delivered Orders -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-green-100 rounded flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-600">Delivered</p>
                    <p class="text-lg font-bold text-gray-900">{{ $recentOrders ? $recentOrders->where('status', 'delivered')->count() : 0 }}</p>
                    <p class="text-[10px] text-gray-500">Orders delivered</p>
                </div>
            </div>
        </div>
        <!-- Pending Orders -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-yellow-100 rounded flex items-center justify-center">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-600">Pending</p>
                    <p class="text-lg font-bold text-gray-900">{{ $recentOrders ? $recentOrders->where('status', 'pending')->count() : 0 }}</p>
                    <p class="text-[10px] text-gray-500">Awaiting delivery</p>
                </div>
            </div>
        </div>
        <!-- Messages -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-400">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8a2 2 0 012-2h2m10-4H7a2 2 0 00-2 2v2a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-600">Messages</p>
                    <p class="text-lg font-bold text-gray-900">{{ $recentMessages ? $recentMessages->count() : 0 }}</p>
                    <p class="text-[10px] text-gray-500">Recent chats</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Orders -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                    <a href="{{ route('customer.order.create') }}" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white rounded hover:bg-green-700 text-xs font-bold">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Place Order
                    </a>
                </div>
                <div class="p-6">
                    @if($recentOrders && $recentOrders->count() > 0)
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
                                        {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' :
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
        <!-- Recent Messages -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Messages</h3>
                    <a href="{{ route('customer.chat.index') }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs font-bold">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8a2 2 0 012-2h2m10-4H7a2 2 0 00-2 2v2a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2z" /></svg>
                        Go to Messages
                    </a>
                </div>
                <div class="p-6">
                    @if($recentMessages && $recentMessages->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentMessages as $message)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $message->sender->name ?? 'Supplier' }}</p>
                                    <p class="text-xs text-gray-500">{{ $message->created_at->format('M d, Y H:i') }}</p>
                                    <div class="text-xs text-gray-700 mt-1">{{ \Illuminate\Support\Str::limit($message->content, 40) }}</div>
                                </div>
                                @if(!$message->is_read)
                                    <span class="ml-2 px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800 font-semibold">New</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8a2 2 0 012-2h2m10-4H7a2 2 0 00-2 2v2a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2z" /></svg>
                            <p class="mt-2 text-sm text-gray-500">No recent messages</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
