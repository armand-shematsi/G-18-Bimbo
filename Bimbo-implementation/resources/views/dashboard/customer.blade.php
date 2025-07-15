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
@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg shadow">
        {{ session('error') }}
    </div>
@endif
@if ($errors->any())
    <div class="mb-4">
        <ul class="text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(isset($segment))
    @if($segment->Segment == 0)
        <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-900 font-semibold shadow">
            Welcome, valued customer! Enjoy exclusive loyalty rewards just for you.
        </div>
    @elseif($segment->Segment == 1)
        <div class="mb-6 p-4 rounded-lg bg-blue-100 text-blue-900 font-semibold shadow">
            Thanks for shopping with us! Check out our new products and special offers.
        </div>
    @elseif($segment->Segment == 2)
        <div class="mb-6 p-4 rounded-lg bg-yellow-100 text-yellow-900 font-semibold shadow">
            We miss you! Here's a special discount to welcome you back.
        </div>
    @endif
@endif
@if(isset($recommendations) && $recommendations)
    <div class="alert alert-info mb-4">
        <h4>Recommended for you:</h4>
        <ul>
            @foreach(explode(';', $recommendations->recommendations) as $rec)
                <li>{{ $rec }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="py-12 min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
            <!-- Edit Profile Card -->
            <div class="flex flex-col items-center bg-white/90 rounded-2xl shadow-lg border border-purple-100 p-8 min-h-[320px] justify-between">
                <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-purple-400 to-purple-600 flex items-center justify-center mb-4 shadow-lg">
                    <span class="text-4xl text-white font-bold">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</span>
                </div>
                <div class="text-center mb-4">
                    <div class="font-extrabold text-xl text-purple-900 mb-1">{{ Auth::user()->name }}</div>
                    <div class="text-purple-700 text-sm mb-4">Customer</div>
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6 6M3 21h18" /></svg>
                        Edit Profile
                    </a>
                </div>
                <div class="hidden lg:block mt-auto text-center text-xs text-gray-400">&copy; {{ date('Y') }} Bimbo. All rights reserved.</div>
            </div>
            <!-- Recent Orders Card -->
            <div class="flex flex-col bg-white/90 rounded-2xl shadow-lg border border-green-100 p-8 min-h-[320px] justify-between">
                        <div>
                            <div class="flex items-center mb-4">
                                <svg class="w-7 h-7 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a4 4 0 004 4h10a4 4 0 004-4V7a4 4 0 00-4-4H7a4 4 0 00-4 4z" /></svg>
                                <h4 class="text-2xl font-bold text-green-900">Recent Orders</h4>
                            </div>
                            @if(isset($recentOrders) && $recentOrders->count())
                                <ul class="divide-y divide-green-100">
                                    @foreach($recentOrders as $order)
                                        <li class="py-4 flex justify-between items-center group">
                                            <div>
                                                <span class="font-semibold text-gray-800">Order #{{ $order->id }}</span>
                                                <span class="ml-2 text-sm text-gray-500">{{ $order->created_at ? $order->created_at->format('Y-m-d') : '-' }}</span>
                                                <span class="ml-2 text-sm text-gray-700">${{ number_format($order->total, 2) }}</span>
                                            </div>
                                            <span class="px-3 py-1 rounded-full text-xs font-bold shadow-sm group-hover:scale-105 transition {{
                                                $order->status === 'delivered' ? 'bg-green-100 text-green-800' :
                                                ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                                ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' :
                                                ($order->status === 'shipped' ? 'bg-indigo-100 text-indigo-800' :
                                                'bg-gray-100 text-gray-800')))
                                            }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-green-700">No recent orders found.</p>
                            @endif
                        </div>
                        <div class="mt-8 flex space-x-3">
                            <a href="{{ route('customer.order.create') }}" class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-green-500 to-green-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest shadow hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Place New Order
                            </a>
                            <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center px-5 py-2 bg-gray-100 border border-gray-200 rounded-lg font-bold text-xs text-gray-800 uppercase tracking-widest shadow hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                View All Orders
                            </a>
                            <a href="{{ route('customer.products') }}" class="inline-flex items-center px-5 py-2 bg-yellow-500 text-white rounded-lg font-bold text-xs uppercase tracking-widest shadow hover:bg-yellow-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                                View Fresh Bread Products
                            </a>
                        </div>
                    </div>
                    <!-- Recent Messages Card -->
                    <div class="flex flex-col bg-white/90 rounded-2xl shadow-lg border border-blue-100 p-8 min-h-[320px] justify-between">
                        <div>
                            <div class="flex items-center mb-4">
                                <svg class="w-7 h-7 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8a2 2 0 012-2h2m10-4H7a2 2 0 00-2 2v2a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2z" /></svg>
                                <h4 class="text-2xl font-bold text-blue-900">Recent Messages</h4>
                            </div>
                            @if(isset($recentMessages) && $recentMessages->count())
                                <ul class="divide-y divide-blue-100">
                                    @foreach($recentMessages as $message)
                                        <li class="py-4 flex justify-between items-center group">
                                            <div>
                                                <span class="font-semibold text-gray-800">{{ $message->sender->name ?? 'Supplier' }}</span>
                                                <span class="ml-2 text-sm text-gray-500">{{ $message->created_at->format('Y-m-d H:i') }}</span>
                                                <div class="text-sm text-gray-700 mt-1">{{ \Illuminate\Support\Str::limit($message->content, 40) }}</div>
                                            </div>
                                            @if(!$message->is_read)
                                                <span class="ml-2 px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800 font-bold shadow group-hover:scale-105 transition">New</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-blue-700">No recent messages.</p>
                            @endif
                        </div>
                        <div class="mt-8">
                            <a href="{{ route('customer.chat.index') }}" class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-blue-500 to-blue-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest shadow hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8a2 2 0 012-2h2m10-4H7a2 2 0 00-2 2v2a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2z" /></svg>
                                Go to Messages
                            </a>
                        </div>
                    </div>
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
                            <!-- Footer -->
                            <div class="mt-16 text-center text-gray-400 text-xs">Thank you for being a valued customer! &mdash; The Bimbo Team</div>
                        </div>
                    </div>
                </div>
                {{-- Reports Button at the bottom --}}
                <div class="w-full flex justify-center mt-8 mb-4">
                    <a href="{{ route('reports.downloads') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg text-xl transition-all duration-200">
                        <i class="fas fa-file-alt mr-2"></i> Reports
                    </a>
                </div>
                <!-- Footer -->
                <div class="mt-16 text-center text-gray-400 text-xs">Thank you for being a valued customer! &mdash; The Bimbo Team</div>
            </main>
        </div>
    </div>
    <!-- Product Gallery -->
    @include('components.product-gallery')
@endsection
