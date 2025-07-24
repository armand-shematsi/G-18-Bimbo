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
        box-shadow: 0 4px 32px 0 rgba(59, 130, 246, 0.10), 0 1.5px 8px 0 rgba(59, 130, 246, 0.06);
        background: rgba(255, 255, 255, 0.95);
        padding: 2.5rem 1.5rem 2rem 1.5rem;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1.5px solid #e0e7ff;
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
    }

    .customer-dashboard-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 2.5rem;
        justify-content: center;
        place-content: center;
        max-width: 1600px;
        margin-left: auto;
        margin-right: auto;
        align-items: stretch;
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

    .customer-dashboard-card {
        min-height: 140px;
        max-width: 500px;
        width: 100%;
        flex: 1 1 0%;
        padding: 2.5rem 3rem;
        border-radius: 1.5rem;
        box-shadow: 0 2px 12px 0 rgba(59, 130, 246, 0.08);
        background: #fff;
        border: 1px solid #e0e7ff;
        transition: box-shadow 0.2s, transform 0.2s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .customer-dashboard-card h4 {
        font-size: 2.2rem;
    }

    .customer-dashboard-card ul.divide-y>li {
        font-size: 1.15rem;
        padding-top: 1.2rem;
        padding-bottom: 1.2rem;
    }

    .customer-dashboard-card:hover {
        box-shadow: 0 8px 32px 0 rgba(59, 130, 246, 0.13);
        transform: translateY(-2px) scale(1.025);
    }

    .customer-dashboard-tips {
        background: linear-gradient(90deg, #f0f9ff 0%, #e0e7ff 100%);
        border-radius: 1.2rem;
        box-shadow: 0 2px 8px 0 rgba(59, 130, 246, 0.06);
        padding: 1.2rem 1rem;
        margin-bottom: 1.5rem;
        font-size: 1rem;
        color: #2563eb;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }

    .customer-dashboard-card .text-green-700,
    .customer-dashboard-card .text-blue-700 {
        text-align: center;
        width: 100%;
        margin: 2.5rem 0 2rem 0;
        font-size: 1.1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 80px;
    }
</style>
<div class="customer-dashboard-bg min-h-screen">
    <div class="customer-dashboard-main">
        <div class="customer-dashboard-tips mb-6">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
            </svg>
            Tip: You can quickly place a new order or chat with your supplier using the buttons below!
        </div>
        <div class="customer-dashboard-grid">
            <!-- Edit Profile Card -->
            <div class="customer-dashboard-card flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-purple-400 to-purple-600 flex items-center justify-center mb-4 shadow-lg animate-fade-in">
                    <span class="text-4xl text-white font-bold">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</span>
                </div>
                <div class="font-extrabold text-xl text-purple-900 mb-1">{{ Auth::user()->name }}</div>
                <div class="text-purple-700 text-sm mb-4">Customer</div>
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-purple-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest shadow hover:from-purple-600 hover:to-purple-800 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6 6M3 21h18" />
                    </svg>
                    Edit Profile
                </a>
                <div class="hidden lg:block mt-auto text-center text-xs text-gray-400 mt-6">&copy; {{ date('Y') }} Bimbo. All rights reserved.</div>
            </div>
            <!-- Recent Orders Card -->
            <div class="customer-dashboard-card flex flex-col items-center text-center">
                <h4 class="text-2xl font-bold text-green-900 mb-4">Recent Orders</h4>
                @if(isset($recentOrders) && $recentOrders->count())
                <ul class="divide-y divide-green-100 w-full">
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
                <p class="text-green-700 w-full text-center my-12">No recent orders found.</p>
                @endif
            </div>
            <!-- Recent Messages Card -->
            <div class="customer-dashboard-card flex flex-col items-center text-center">
                <h4 class="text-2xl font-bold text-blue-900 mb-4">Recent Messages</h4>
                @if(isset($recentMessages) && $recentMessages->count())
                <ul class="divide-y divide-blue-100 w-full">
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
                <p class="text-blue-700 w-full text-center my-12">No recent messages found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection