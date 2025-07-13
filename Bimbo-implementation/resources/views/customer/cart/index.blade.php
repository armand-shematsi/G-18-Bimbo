@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Your Cart</h1>
        <a href="{{ route('customer.order.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Continue Shopping</a>
    </div>
@endsection

@section('content')
<div class="py-8 max-w-4xl mx-auto">
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg shadow">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg shadow">{{ session('error') }}</div>
    @endif
    <div class="bg-white rounded-2xl shadow-lg p-6">
        @if(count($cart) > 0)
            <table class="min-w-full divide-y divide-gray-200 mb-6">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Unit Price</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($cart as $index => $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-semibold">{{ $item['product_name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('customer.cart.update', $index) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 px-2 py-1 border border-gray-300 rounded-md text-sm" onchange="this.form.submit()">
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-green-700">₦{{ number_format($item['unit_price'], 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold">₦{{ number_format($item['total_price'], 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('customer.cart.destroy', $index) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="flex justify-between items-center mb-6">
                <div class="text-lg font-bold text-gray-700">Total: <span class="text-green-700">₦{{ number_format($total, 2) }}</span></div>
                <a href="{{ route('customer.cart.checkout') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow transition-all">Proceed to Checkout</a>
            </div>
        @else
            <div class="text-center text-gray-500 text-lg">Your cart is empty.</div>
        @endif
    </div>
</div>
@endsection 