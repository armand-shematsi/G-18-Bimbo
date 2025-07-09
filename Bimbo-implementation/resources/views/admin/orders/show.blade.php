@extends('layouts.dashboard')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
            <p class="mt-1 text-sm text-gray-600">Order details and management.</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.orders.edit', $order) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Order
            </a>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Orders
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Order Status -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Order Status</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                               ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' :
                               ($order->status === 'shipped' ? 'bg-purple-100 text-purple-800' :
                               ($order->status === 'delivered' ? 'bg-green-100 text-green-800' :
                               'bg-red-100 text-red-800'))) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        <span class="ml-4 text-sm text-gray-500">Order placed on {{ $order->created_at->format('M d, Y \a\t H:i') }}</span>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-gray-900">${{ number_format($order->total, 2) }}</div>
                        <div class="text-sm text-gray-500">{{ $order->items->count() }} items</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Information -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Customer Information</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Customer Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->customer_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->customer_email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Vendor</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->vendor->name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($order->payment_status ?? 'unpaid') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Order Items</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $item->product_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${{ number_format($item->unit_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${{ number_format($item->total_price, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Shipping & Delivery -->
                @if($order->shipping_address || $order->billing_address || $order->fulfillment_type || $order->delivery_option || $order->tracking_number)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Shipping & Delivery</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            @if($order->fulfillment_type)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fulfillment Type</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->fulfillment_type }}</dd>
                            </div>
                            @endif
                            @if($order->delivery_option)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Delivery Option</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->delivery_option }}</dd>
                            </div>
                            @endif
                            @if($order->tracking_number)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tracking Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->tracking_number }}</dd>
                            </div>
                            @endif
                            @if($order->shipping_address)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Shipping Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_address }}</dd>
                            </div>
                            @endif
                            @if($order->billing_address)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Billing Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->billing_address }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Order Summary -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Order Summary</h3>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Order ID</dt>
                                <dd class="text-sm text-gray-900">#{{ $order->id }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Placed</dt>
                                <dd class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y H:i') }}</dd>
                            </div>
                            @if($order->placed_at)
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Placed At</dt>
                                <dd class="text-sm text-gray-900">{{ $order->placed_at->format('M d, Y H:i') }}</dd>
                            </div>
                            @endif
                            @if($order->delivered_at)
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Delivered</dt>
                                <dd class="text-sm text-gray-900">{{ $order->delivered_at->format('M d, Y H:i') }}</dd>
                            </div>
                            @endif
                            <div class="border-t pt-4">
                                <div class="flex justify-between">
                                    <dt class="text-base font-medium text-gray-900">Total</dt>
                                    <dd class="text-base font-medium text-gray-900">${{ number_format($order->total, 2) }}</dd>
                                </div>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Notes -->
                @if($order->notes)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Notes</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-900">{{ $order->notes }}</p>
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('admin.orders.edit', $order) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Edit Order
                        </a>
                        @if($order->user)
                        <a href="mailto:{{ $order->customer_email }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Contact Customer
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 