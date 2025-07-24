@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Our Fresh Bread Products') }}
</h2>
@endsection

@section('content')


@php
// Remove $accentColors and related logic
@endphp
<style>
    .product-card {
        --accent-color: #3b82f6;
        --shadow-color: rgba(59, 130, 246, 0.13);
        transition: box-shadow 0.25s, transform 0.18s, border-color 0.18s;
        background: #fff;
        border-radius: 1.2rem;
        border: 2px solid #e5e7eb;
        position: relative;
        min-height: 420px;
        max-width: 400px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        margin-bottom: 1rem;
        padding-top: 2rem;
        padding-bottom: 2rem;
        padding-left: 1.5rem;
        padding-right: 1.5rem;
        box-shadow: 0 2px 12px 0 var(--shadow-color);
    }

    .product-card:hover {
        box-shadow: 0 8px 20px 0 var(--shadow-color, rgba(59, 130, 246, 0.18));
        transform: translateY(-4px) scale(1.025);
        border-color: var(--accent-color, #3b82f6);
        background: #f9fafb;
    }

    .product-img {
        border-radius: 50%;
        border: 3px solid var(--accent-color, #3b82f6);
        width: 180px;
        height: 180px;
        object-fit: cover;
        background: #fff;
        margin-bottom: 1.2rem;
        box-shadow: 0 3px 12px 0 var(--shadow-color);
        transition: box-shadow 0.2s, border-color 0.2s;
        z-index: 2;
    }

    .product-card:hover .product-img {
        border-color: var(--accent-color, #3b82f6);
        box-shadow: 0 8px 20px 0 var(--shadow-color, rgba(59, 130, 246, 0.18));
    }

    .badge {
        position: absolute;
        top: 0.7rem;
        left: 50%;
        transform: translateX(-50%) translateY(-30%);
        background: var(--accent-color, #3b82f6);
        color: #fff;
        font-size: 0.85rem;
        font-weight: 800;
        padding: 0.18rem 0.7rem;
        border-radius: 9999px;
        box-shadow: 0 1px 4px 0 var(--shadow-color, rgba(59, 130, 246, 0.13));
        letter-spacing: 0.07em;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
        z-index: 3;
    }

    .add-to-cart-btn {
        background: var(--accent-color, #3b82f6);
        color: #fff;
        font-weight: 800;
        border: none;
        border-radius: 0.7rem;
        padding: 0.6rem 1.2rem;
        font-size: 1rem;
        box-shadow: 0 2px 8px 0 var(--shadow-color, rgba(59, 130, 246, 0.13));
        transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.7rem;
    }

    .add-to-cart-btn:hover {
        background: #111827;
        color: #fff;
        box-shadow: 0 4px 16px 0 var(--shadow-color, rgba(59, 130, 246, 0.22));
        transform: scale(1.04);
    }

    .product-title {
        font-size: 1.1rem;
        font-weight: 900;
        margin-bottom: 0.3rem;
        color: #111827;
        text-align: center;
        letter-spacing: 0.01em;
    }

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

    .out-of-stock {
        color: #ef4444;
        font-weight: 800;
        font-size: 0.95rem;
        margin-top: 1.2rem;
    }

    .quantity-input {
        width: 40px;
        font-size: 0.95rem;
        padding: 0.3rem;
        border-radius: 0.3rem;
        border: 1.2px solid #d1fae5;
        text-align: center;
        margin-bottom: 0.3rem;
        font-weight: 700;
        color: #059669;
        background: #f0fdf4;
        transition: border-color 0.2s;
    }

    .quantity-input:focus {
        border-color: #3b82f6;
        outline: none;
    }
</style>
<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-4 gap-y-6 mt-6">
    @forelse($products as $product)
    <div class="product-card p-10">
        <span class="badge">Fresh Bread</span>
        @if($product->image_url)
        <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" class="product-img">
        @else
        <div class="product-img flex items-center justify-center bg-gray-200">
            <span class="text-gray-400 text-5xl">🍞</span>
        </div>
        @endif
        <h3 class="text-2xl font-extrabold text-gray-900 text-center mb-1 tracking-tight">{{ $product->name }}</h3>
        @if(isset($product->statement) && $product->statement)
        <p class="text-base text-gray-500 italic text-center mb-2">{{ $product->statement }}</p>
        @endif
        <div class="flex items-center justify-center gap-4 mb-2">
            <div class="flex items-center gap-1">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.045 9.394c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.967z" /></svg>
                    @endfor
                    <span class="ml-1 text-yellow-600 font-bold text-base">{{ number_format($product->rating ?? 5, 1) }}</span>
            </div>
            <span class="bg-green-100 text-green-700 font-bold px-3 py-1 rounded-full text-xs shadow">Available: {{ $product->available ?? 0 }} {{ $product->unit ?? 'loaf' }}</span>
        </div>
        <p class="mb-1 text-gray-500 text-base">Available: <span class="product-available">{{ $product->available }} {{ $product->unit }}</span></p>
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


