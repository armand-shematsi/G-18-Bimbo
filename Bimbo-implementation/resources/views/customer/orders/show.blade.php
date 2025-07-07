@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Order #{{ $order->id }}</h2>
                    <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Back to Orders
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Order Summary -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Order Information</h3>
                            <div class="space-y-2 text-sm text-gray-600">
                                <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                                <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                                <p><strong>Status:</strong> 
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                        @elseif($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Customer Information</h3>
                            <div class="space-y-2 text-sm text-gray-600">
                                <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                                <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Order Summary</h3>
                            <div class="space-y-2 text-sm text-gray-600">
                                <p><strong>Total Items:</strong> {{ $order->items->count() }}</p>
                                <p><strong>Total Amount:</strong> <span class="font-semibold text-lg text-gray-900">${{ number_format($order->total_amount, 2) }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ${{ number_format($item->total_price, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Order Actions -->
                <div class="flex justify-between items-center">
                    <div>
                        @if($order->status === 'pending')
                            <form action="{{ route('customer.orders.cancel', $order) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('Are you sure you want to cancel this order?')">
                                    Cancel Order
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Order Total</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 