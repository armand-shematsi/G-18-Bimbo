@extends('layouts.supplier')

@section('header')
    <h1 class="text-3xl font-bold text-gray-900">Your Raw Materials Cart</h1>
    <p class="mt-1 text-sm text-gray-600">Review and manage your selected raw materials before placing an order.</p>
@endsection

@section('content')
@if (
    session('error') || $errors->any()
)
    <div class="alert alert-danger bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        @if (session('error'))
            <div>{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endif
<div class="py-8 max-w-4xl mx-auto">
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
                                <form action="{{ route('supplier.raw-materials.removeFromCart', $index) }}" method="POST" style="display:inline">
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
            <form action="{{ route('supplier.raw-materials.checkout') }}" method="POST" class="bg-gray-50 rounded-lg p-6 shadow-inner mt-8">
                @csrf
                <div class="mb-4">
                    <label for="shipping_address" class="block text-gray-700 font-bold mb-2">Shipping Address</label>
                    <input type="text" name="shipping_address" id="shipping_address" class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('shipping_address', 'Test Address') }}" required>
                </div>
                <div class="mb-4">
                    <label for="billing_address" class="block text-gray-700 font-bold mb-2">Billing Address</label>
                    <input type="text" name="billing_address" id="billing_address" class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('billing_address', 'Test Address') }}" required>
                </div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow">Place Order</button>
            </form>
        @else
            <div class="text-center text-gray-500 text-lg py-12">Your cart is empty. <a href="{{ route('supplier.raw-materials.catalog') }}" class="text-blue-600 hover:underline">Browse raw materials</a> to get started.</div>
        @endif
    </div>
</div>
@endsection 