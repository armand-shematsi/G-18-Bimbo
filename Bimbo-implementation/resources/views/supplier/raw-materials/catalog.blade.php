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
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 flex flex-col p-6 hover:shadow-2xl hover:border-blue-400 transition-all group relative overflow-hidden">
                @if($item->product && $item->product->image_url)
                    <div class="overflow-hidden rounded-lg mb-4 border border-gray-200 shadow-sm">
                        <img src="{{ asset($item->product->image_url) }}" alt="{{ $item->item_name }}" class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                @else
                    <div class="overflow-hidden rounded-lg mb-4 border border-gray-200 shadow-sm">
                        <img src="{{ asset('images/default-product.jpg') }}" alt="{{ $item->item_name }}" class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                @endif
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-extrabold text-blue-900">{{ $item->item_name }}</h2>
                    <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-700 font-semibold shadow">{{ ucfirst($item->unit) }}</span>
                </div>
                <div class="mb-2 text-gray-700 flex items-center">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-purple-100 text-purple-700 font-bold mr-2" title="Supplier: {{ $item->user->name ?? 'N/A' }}">
                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                    </span>
                    <span>Supplier: <span class="font-semibold text-purple-700">{{ $item->user->name ?? 'N/A' }}</span></span>
                </div>
                <div class="mb-2 text-gray-700">Available: <span class="font-bold text-green-700">{{ $item->quantity }}</span></div>
                <div class="mb-2 text-gray-700 text-lg flex items-center">
                    <span class="font-bold text-green-700 text-2xl">₦{{ number_format($item->unit_price, 2) }}</span>
                    <span class="text-xs text-gray-400 ml-1">per {{ $item->unit }}</span>
                    <!-- Static star rating -->
                    <span class="ml-2 text-yellow-400">★★★★☆</span>
                </div>
                <form action="{{ route('supplier.raw-materials.addToCart') }}" method="POST" class="mt-auto">
                    @csrf
                    <input type="hidden" name="inventory_id" value="{{ $item->id }}">
                    <div class="flex items-center mb-3">
                        <label for="quantity-{{ $item->id }}" class="mr-2 font-medium text-gray-700">Qty:</label>
                        <input type="number" name="quantity" id="quantity-{{ $item->id }}" value="1" min="1" max="{{ $item->quantity }}" class="w-20 border border-gray-300 rounded-lg px-2 py-1 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white font-bold py-2 px-4 rounded-lg shadow transition-all duration-150 transform hover:scale-105 relative overflow-hidden"
                        @if($item->quantity == 0) disabled class="opacity-50 cursor-not-allowed" @endif>
                        <span class="relative z-10">
                            <svg class="inline-block w-5 h-5 mr-2 -mt-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A2 2 0 007.6 19h8.8a2 2 0 001.95-2.3L17 13M7 13V6h13"></path></svg>
                            Add to Cart
                        </span>
                    </button>
                </form>
                @if($item->quantity == 0)
                    <span class="absolute top-4 right-4 bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full shadow">Out of Stock</span>
                @elseif($item->quantity <= 5)
                    <span class="absolute top-4 right-4 bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full shadow">Low Stock</span>
                @else
                <span class="absolute top-4 right-4 bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full shadow">In Stock</span>
                @endif
                <div class="mt-4 text-xs text-gray-400 text-right">Last updated: {{ $item->updated_at->diffForHumans() }}</div>
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
