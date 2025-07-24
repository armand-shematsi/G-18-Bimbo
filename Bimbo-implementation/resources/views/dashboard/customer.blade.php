@extends('layouts.app')

@section('header')
    <div class="relative bg-gradient-to-r from-blue-400 via-green-200 to-purple-200 rounded-xl shadow-xl overflow-hidden mb-8 p-8 flex flex-col sm:flex-row items-center justify-between">
        <div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2 drop-shadow-lg">Customer Dashboard</h1>
            <p class="text-white text-base opacity-90">Welcome back, {{ Auth::user()->name }}! Here's your activity overview.</p>
        </div>
        <div class="mt-6 sm:mt-0 text-right animate-fade-in">
            <p class="text-xs text-white/80">Last updated</p>
            <p class="text-sm font-semibold text-white">{{ now()->format('M d, Y H:i') }}</p>
        </div>
    </div>
@endsection

@section('content')
<style>
    .customer-dashboard-bg {
        background: linear-gradient(120deg, #e0e7ff 0%, #f0f9ff 100%);
        min-height: 100vh;
    }
    .customer-dashboard-main {
        border-radius: 2rem;
        box-shadow: 0 4px 32px 0 rgba(59,130,246,0.10), 0 1.5px 8px 0 rgba(59,130,246,0.06);
        background: rgba(255,255,255,0.95);
        padding: 2.5rem 1.5rem 2rem 1.5rem;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1.5px solid #e0e7ff;
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
    }
    .customer-dashboard-gradient {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 100%);
        border-radius: 2rem;
        padding: 2rem 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        min-height: 400px;
    }
    .customer-dashboard-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 1.5rem;
        justify-content: center;
        place-content: center;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }
    @media (min-width: 640px) {
        .customer-dashboard-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (min-width: 1024px) {
        .customer-dashboard-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    @media (min-width: 1280px) {
        .customer-dashboard-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }
    .customer-dashboard-card {
        min-height: 100px;
        max-width: 520px;
        padding: 1rem 2.5rem;
        border-radius: 1.5rem;
        box-shadow: 0 2px 12px 0 rgba(59,130,246,0.08);
        background: #fff;
        border: 1px solid #e0e7ff;
        transition: box-shadow 0.2s, transform 0.2s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .customer-dashboard-card:hover {
        box-shadow: 0 8px 32px 0 rgba(59,130,246,0.13);
        transform: translateY(-2px) scale(1.025);
    }
    .customer-dashboard-tips {
        background: linear-gradient(90deg, #f0f9ff 0%, #e0e7ff 100%);
        border-radius: 1.2rem;
        box-shadow: 0 2px 8px 0 rgba(59,130,246,0.06);
        padding: 1.2rem 1rem;
        margin-bottom: 1.5rem;
        font-size: 1rem;
        color: #2563eb;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }
</style>
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
<div class="customer-dashboard-bg min-h-screen">
    <div class="customer-dashboard-main">
        <div class="customer-dashboard-tips mb-6">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" /></svg>
            Tip: You can quickly place a new order or chat with your supplier using the buttons below!
        </div>
        <div class="customer-dashboard-gradient">
            <div class="customer-dashboard-grid">
                <!-- Edit Profile Card -->
                <div class="customer-dashboard-card flex flex-col items-center justify-between">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-purple-400 to-purple-600 flex items-center justify-center mb-4 shadow-lg animate-fade-in">
                        <span class="text-4xl text-white font-bold">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</span>
                    </div>
                    <div class="text-center mb-4">
                        <div class="font-extrabold text-xl text-purple-900 mb-1">{{ Auth::user()->name }}</div>
                        <div class="text-purple-700 text-sm mb-4">Customer</div>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-purple-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest shadow hover:from-purple-600 hover:to-purple-800 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6 6M3 21h18" /></svg>
                            Edit Profile
                        </a>
                    </div>
                    <div class="hidden lg:block mt-auto text-center text-xs text-gray-400">&copy; {{ date('Y') }} Bimbo. All rights reserved.</div>
                </div>
                <!-- Recent Orders Card -->
                <div class="customer-dashboard-card flex flex-col justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <svg class="w-7 h-7 text-green-600 mr-3 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a4 4 0 004 4h10a4 4 0 004-4V7a4 4 0 00-4-4H7a4 4 0 00-4 4z" /></svg>
                            <h4 class="text-2xl font-bold text-green-900">Recent Orders</h4>
                        </div>
                        @if(isset($recentOrders) && $recentOrders->count())
                            <ul class="divide-y divide-green-100">
                                @foreach($recentOrders->take(5) as $order)
                                    @php
                                        $statusClass = match($order->status) {
                                            'delivered' => 'bg-green-100 text-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'processing' => 'bg-blue-100 text-blue-800',
                                            'shipped' => 'bg-indigo-100 text-indigo-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <li class="py-4 flex justify-between items-center group">
                                        <div>
                                            <span class="font-semibold text-gray-800">Order #{{ $order->id }}</span>
                                            <span class="ml-2 text-sm text-gray-500">{{ $order->created_at ? $order->created_at->format('Y-m-d') : '-' }}</span>
                                            <span class="ml-2 text-sm text-gray-700">${{ number_format($order->total, 2) }}</span>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-xs font-bold shadow-sm group-hover:scale-110 transition {{ $statusClass }}">
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
                        <a href="{{ route('customer.order.create') }}" class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-green-400 to-green-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest shadow hover:from-green-500 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            Place New Order
                        </a>
                        <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-gray-200 to-gray-300 border border-gray-200 rounded-lg font-bold text-xs text-gray-800 uppercase tracking-widest shadow hover:from-gray-300 hover:to-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            View All Orders
                        </a>
                    </div>
                </div>
                <!-- Recent Messages Card -->
                <div class="customer-dashboard-card flex flex-col justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <svg class="w-7 h-7 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z" /></svg>
                            <h4 class="text-2xl font-bold text-blue-900">Recent Messages</h4>
                        </div>
                        @if(isset($recentMessages) && $recentMessages->count())
                            <ul class="divide-y divide-blue-100">
                                @foreach($recentMessages as $message)
                                    <li class="py-4 flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3 text-blue-600 font-bold text-lg">
                                            {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $message->sender->name }}</p>
                                            <p class="text-sm text-gray-700">{{ $message->message }}</p>
                                            <p class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-blue-700">No recent messages found.</p>
                        @endif
                    </div>
                    <div class="mt-8 flex space-x-3">
                        <a href="{{ route('customer.chat.index') }}" class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-blue-500 to-blue-700 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest shadow hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z" /></svg>
                            View All Messages
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full flex flex-col md:flex-row justify-center gap-4 mt-6">
            <a href="{{ route('customer.chat.index') }}" class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-lg font-bold text-xs uppercase tracking-widest shadow hover:from-blue-600 hover:to-blue-800 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8a2 2 0 012-2h2m10-4H7a2 2 0 00-2 2v2a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2z" /></svg>
                Chat with Supplier
            </a>
            <a href="{{ route('reports.downloads') }}" class="bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg text-xl transition-all duration-200">
                <i class="fas fa-file-alt mr-2"></i> Reports
            </a>
        </div>
    </div>
</div>
@endsection
