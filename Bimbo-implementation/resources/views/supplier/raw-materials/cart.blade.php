@extends('layouts.supplier')

@section('header')
    <h1 class="text-3xl font-bold text-gray-900">Your Raw Materials Cart</h1>
    <p class="mt-1 text-sm text-gray-600">Review and manage your selected raw materials before placing an order.</p>
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
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Supplier</th>
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
                            <td class="px-6 py-4 whitespace-nowrap text-purple-700">@php $supplier = \App\Models\User::find($item['supplier_id']); @endphp {{ $supplier->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item['quantity'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-green-700">₦{{ number_format($item['unit_price'], 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold">₦{{ number_format($item['total_price'], 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('supplier.raw-materials.removeFromCart', $index) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="flex justify-between items-center mb-6">
                <div class="text-lg font-bold text-gray-700">Total: <span class="text-green-700">₦{{ number_format($total, 2) }}</span></div>
                <a href="{{ route('supplier.raw-materials.catalog') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition-all">Continue Shopping</a>
            </div>
            <form action="{{ route('supplier.raw-materials.checkout') }}" method="POST" class="bg-gray-50 rounded-lg p-6 shadow-inner">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Shipping Address</label>
                        <input type="text" name="shipping_address" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Billing Address</label>
                        <input type="text" name="billing_address" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400" required>
                    </div>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-blue-500 hover:from-blue-700 hover:to-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg text-lg transition-all">Place Order</button>
            </form>
        @else
            <div class="text-center text-gray-500 text-lg py-12">Your cart is empty. <a href="{{ route('supplier.raw-materials.catalog') }}" class="text-blue-600 hover:underline">Browse raw materials</a> to get started.</div>
        @endif
    </div>
</div>
@endsection 