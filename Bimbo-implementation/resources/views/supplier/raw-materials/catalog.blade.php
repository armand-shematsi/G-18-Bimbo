@extends('layouts.supplier')

@section('header')
    <h1 class="text-3xl font-bold text-gray-900">Order Raw Materials</h1>
    <p class="mt-1 text-sm text-gray-600">Browse and order raw materials from other suppliers.</p>
@endsection

@section('content')
<div class="py-8">
    {{-- Debug print removed --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg shadow">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg shadow">{{ session('error') }}</div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($rawMaterials as $item)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 flex flex-col p-6 hover:shadow-2xl transition-all">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-blue-800">{{ $item->item_name }}</h2>
                    <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-700">{{ ucfirst($item->unit) }}</span>
                </div>
                <div class="mb-2 text-gray-700">Supplier: <span class="font-semibold text-purple-700">{{ $item->user->name ?? 'N/A' }}</span></div>
                <div class="mb-2 text-gray-700">Available: <span class="font-semibold">{{ $item->quantity }}</span></div>
                <div class="mb-2 text-gray-700">Unit Price: <span class="font-semibold text-green-700">₦{{ number_format($item->unit_price, 2) }}</span></div>
                <form action="{{ route('supplier.raw-materials.addToCart') }}" method="POST" class="mt-auto">
                    @csrf
                    <input type="hidden" name="inventory_id" value="{{ $item->id }}">
                    <div class="flex items-center mb-3">
                        <label for="quantity-{{ $item->id }}" class="mr-2 font-medium text-gray-700">Qty:</label>
                        <input type="number" name="quantity" id="quantity-{{ $item->id }}" value="1" min="1" max="{{ $item->quantity }}" class="w-20 border border-gray-300 rounded-lg px-2 py-1 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white font-bold py-2 px-4 rounded-lg shadow transition-all duration-150 transform hover:scale-105">Add to Cart</button>
                </form>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-500 text-lg">No raw materials available from other suppliers at the moment.</div>
        @endforelse
    </div>
    <div class="mt-8 text-right">
        <a href="{{ route('supplier.raw-materials.cart') }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg shadow transition-all">View Cart</a>
    </div>
</div>
@endsection 