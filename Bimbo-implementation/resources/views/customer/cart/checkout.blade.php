@extends('layouts.app')

@section('header')
    <h1 class="text-2xl font-bold text-gray-900">Checkout</h1>
@endsection

@section('content')
<div class="py-8 max-w-4xl mx-auto">
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg shadow">{{ session('error') }}</div>
    @endif
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
        <table class="min-w-full divide-y divide-gray-200 mb-6">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Unit Price</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach($cart as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-semibold">{{ $item['product_name'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item['quantity'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-green-700">₦{{ number_format($item['unit_price'], 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-bold">₦{{ number_format($item['total_price'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-lg font-bold text-gray-700 mb-6">Total: <span class="text-green-700">₦{{ number_format($total, 2) }}</span></div>
        <form action="{{ route('customer.cart.place-order') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Shipping Address</label>
                <input type="text" name="shipping_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Billing Address</label>
                <input type="text" name="billing_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            </div>
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow transition-all">Place Order</button>
        </form>
    </div>
</div>
@endsection 