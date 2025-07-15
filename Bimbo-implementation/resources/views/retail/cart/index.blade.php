@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Shopping Cart</h1>
            <p class="mt-1 text-sm text-gray-600">Review your items and proceed to checkout</p>
        </div>
        <div class="text-right">
            <a href="{{ route('retail.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Continue Shopping
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(count($cart) > 0)
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Cart Items ({{ count($cart) }})</h2>
                </div>
                
                <div class="divide-y divide-gray-200">
                    @foreach($cart as $index => $item)
                        <div class="p-6 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $item['product_name'] }}</h3>
                                    <p class="text-sm text-gray-500">Unit Price: ₦{{ number_format($item['unit_price'], 2) }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2">
                                    <label for="quantity-{{ $index }}" class="text-sm font-medium text-gray-700">Qty:</label>
                                    <form action="{{ route('retail.cart.update', $index) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" 
                                               name="quantity" 
                                               value="{{ $item['quantity'] }}" 
                                               min="1" 
                                               max="99"
                                               class="w-16 px-2 py-1 border border-gray-300 rounded-md text-sm"
                                               onchange="this.form.submit()">
                                    </form>
                                </div>
                                
                                <div class="text-right">
                                    <p class="text-lg font-semibold text-gray-900">₦{{ number_format($item['total_price'], 2) }}</p>
                                </div>
                                
                                <form action="{{ route('retail.cart.destroy', $index) }}" method="POST" class="ml-4">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 transition"
                                            onclick="return confirm('Are you sure you want to remove this item?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-lg font-semibold text-gray-900">Total: ₦{{ number_format($total, 2) }}</p>
                            <p class="text-sm text-gray-600">{{ count($cart) }} item(s)</p>
                        </div>
                        <div class="flex space-x-4">
                            <a href="{{ route('retail.products.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Continue Shopping
                            </a>
                            <a href="{{ route('retail.cart.checkout') }}" 
                               class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Your cart is empty</h3>
                <p class="mt-1 text-sm text-gray-500">Start shopping to add items to your cart.</p>
                <div class="mt-6">
                    <a href="{{ route('retail.products.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Start Shopping
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection 