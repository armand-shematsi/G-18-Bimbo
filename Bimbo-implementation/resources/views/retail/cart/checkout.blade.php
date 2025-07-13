@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Checkout</h1>
            <p class="mt-1 text-sm text-gray-600">Complete your order</p>
        </div>
        <div class="text-right">
            <a href="{{ route('retail.cart.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Cart
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Order Summary -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
                </div>
                
                <div class="divide-y divide-gray-200">
                    @foreach($cart as $index => $item)
                        <div class="p-6 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-900">{{ $item['product_name'] }}</h3>
                                    <p class="text-sm text-gray-500">Qty: {{ $item['quantity'] }} × ₦{{ number_format($item['unit_price'], 2) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">₦{{ number_format($item['total_price'], 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <p class="text-lg font-semibold text-gray-900">Total</p>
                        <p class="text-lg font-semibold text-gray-900">₦{{ number_format($total, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Order Details</h2>
                </div>
                
                <form action="{{ route('retail.cart.place-order') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    
                    <div>
                        <label for="shipping_address" class="block text-sm font-medium text-gray-700">Shipping Address</label>
                        <textarea id="shipping_address" 
                                  name="shipping_address" 
                                  rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                  placeholder="Enter your shipping address"
                                  required>{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="billing_address" class="block text-sm font-medium text-gray-700">Billing Address</label>
                        <textarea id="billing_address" 
                                  name="billing_address" 
                                  rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                  placeholder="Enter your billing address"
                                  required>{{ old('billing_address') }}</textarea>
                        @error('billing_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select id="payment_method" 
                                name="payment_method" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                            <option value="">Select payment method</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash on Delivery</option>
                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                            <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <div>
                            <p class="text-sm text-gray-600">Total Amount</p>
                            <p class="text-2xl font-bold text-gray-900">₦{{ number_format($total, 2) }}</p>
                        </div>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
