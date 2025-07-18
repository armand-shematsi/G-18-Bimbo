@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Place New Order') }}
    </h2>
@endsection

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-xl p-8 border border-gray-100">
            <h3 class="text-2xl font-bold text-green-800 mb-6 flex items-center">
                <svg class="w-7 h-7 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a4 4 0 004 4h10a4 4 0 004-4V7a4 4 0 00-4-4H7a4 4 0 00-4 4z" /></svg>
                Order Products
            </h3>
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
            <div class="flex justify-end mb-4">
                <a href="{{ route('customer.cart.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">View Cart</a>
            </div>
            <table class="min-w-full mb-6 divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Available</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Add to Cart</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="px-4 py-2 font-semibold text-gray-800">{{ $product->name }}</td>
                        <td class="px-4 py-2 text-green-700 font-bold">${{ number_format($product->unit_price, 2) }}</td>
                        <td class="px-4 py-2 text-gray-700">{{ $product->available }} {{ $product->unit }}</td>
                        <td class="px-4 py-2">
                            <form method="POST" action="{{ route('customer.cart.add') }}" class="flex items-center">
                                @csrf
                                <input type="hidden" name="inventory_id" value="{{ $product->inventory_id }}">
                                <input type="number" name="quantity" min="1" max="{{ $product->available }}" value="1" class="w-20 border rounded px-2 py-1 mr-2">
                                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Add to Cart</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">No products available.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Place Order
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
