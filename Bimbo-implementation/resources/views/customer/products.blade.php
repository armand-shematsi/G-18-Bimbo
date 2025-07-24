@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Our Fresh Bread Products') }}
    </h2>
@endsection

@section('content')
@if(isset($products) && count($products))
<div x-data="{ toast: '', showToast(msg) { this.toast = msg; setTimeout(() => this.toast = '', 1800); } }">
    <template x-if="toast">
        <div class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-green-600 text-white font-bold px-6 py-3 rounded-2xl shadow-lg animate-fade-in-out">
            <span x-text="toast"></span>
        </div>
    </template>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
        @foreach($products as $product)
            <div class="bg-gradient-to-br from-blue-100 to-blue-300 rounded-3xl shadow-xl p-7 flex flex-col items-center transition-transform duration-200 hover:-translate-y-2 hover:shadow-2xl group relative"
                 x-data="{ loading: false, quantity: 1, max: {{ $product->available ?? 1 }}, shake: false, handleInput(e) { if (parseInt(e.target.value) > this.max) { this.shake = true; this.quantity = this.max; setTimeout(() => this.shake = false, 400); } }, handleSubmit(e) { if (this.quantity > this.max) { this.shake = true; this.quantity = this.max; setTimeout(() => this.shake = false, 400); e.preventDefault(); return; } this.loading = true; } }">
                <!-- Product Type Badge (if available) -->
                @if(isset($product->type) && $product->type)
                    <span class="absolute top-5 left-5 bg-gradient-to-r from-green-400 to-blue-400 text-white text-xs font-bold px-3 py-1 rounded-full shadow">{{ ucfirst(str_replace('_', ' ', $product->type)) }}</span>
                @endif
                <!-- Product Image -->
                <div class="w-40 h-45 mb-4 flex items-center justify-center rounded-full border-4 border-blue-400 bg-gradient-to-br from-blue-100 to-purple-100 overflow-hidden shadow-lg transition-all duration-200 group-hover:border-green-400">
                    <img src="{{ asset($product->image_url ?? 'images/default-product.jpg') }}" alt="{{ $product->name }}" class="object-cover w-36 h-36 rounded-full">
                </div>
                <!-- Product Name -->
                <h3 class="text-2xl font-extrabold text-gray-900 text-center mb-1 tracking-tight">{{ $product->name }}</h3>
                <!-- Product Statement -->
                @if(isset($product->statement) && $product->statement)
                    <p class="text-base text-gray-500 italic text-center mb-2">{{ $product->statement }}</p>
                @endif
                <!-- Rating and Stock Group -->
                <div class="flex items-center justify-center gap-4 mb-2">
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.045 9.394c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                        @endfor
                        <span class="ml-1 text-yellow-600 font-bold text-base">{{ number_format($product->rating ?? 5, 1) }}</span>
                    </div>
                    <span class="bg-green-100 text-green-700 font-bold px-3 py-1 rounded-full text-xs shadow">Available: {{ $product->available ?? 0 }} {{ $product->unit ?? 'loaf' }}</span>
                </div>
                <p class="product-price">$ {{ number_format($product->unit_price, 2) }}</p>
                @if($product->available > 0 && $product->inventory_id)
                    <form action="{{ route('customer.cart.add') }}" method="POST" class="flex flex-col items-center w-full">
                        @csrf
                        @if(isset($product->inventory_id))
                            <input type="hidden" name="inventory_id" value="{{ $product->inventory_id }}">
                        @endif
                        <input type="number" name="quantity" min="1" max="{{ $product->available ?? 1 }}" value="1" class="w-16 h-12 text-center rounded-xl border-2 border-green-200 focus:border-green-400 focus:ring-2 focus:ring-green-100 text-lg font-bold bg-green-50 mb-2 transition">
                        @if(($product->available ?? 0) == 0)
                            <span class="w-full text-center bg-red-100 text-red-600 font-bold py-2 rounded-xl shadow mb-2">Out of Stock</span>
                        @endif
                        <button type="submit" @if(($product->available ?? 0) == 0) disabled @endif class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-blue-500 to-blue-400 hover:from-blue-600 hover:to-blue-500 text-white font-extrabold text-lg py-3 rounded-2xl shadow-lg transition-transform duration-150 hover:-translate-y-1 focus:outline-none disabled:opacity-60 disabled:cursor-not-allowed">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A2 2 0 007.48 19h9.04a2 2 0 001.83-2.3L17 13M7 13V6a1 1 0 011-1h5a1 1 0 011 1v7"></path></svg>
                            Add to Cart
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</div>
@else
    <div class="text-center text-gray-500 mt-12">
        No products available at this time.
    </div>
@endif
<style>
@keyframes fade-in-out {
    0% { opacity: 0; transform: translateY(-10px); }
    10% { opacity: 1; transform: translateY(0); }
    90% { opacity: 1; transform: translateY(0); }
    100% { opacity: 0; transform: translateY(-10px); }
}
.animate-fade-in-out { animation: fade-in-out 1.8s both; }
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    20%, 60% { transform: translateX(-8px); }
    40%, 80% { transform: translateX(8px); }
}
.animate-shake { animation: shake 0.4s; }
.product-available {
    color: #059669;
    font-weight: 700;
    font-size: 0.95rem;
}
.product-price {
    font-size: 1.2rem;
    font-weight: 900;
    color: #059669;
    margin-bottom: 0.7rem;
    letter-spacing: 0.01em;
}
</style>
@endsection


