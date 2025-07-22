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
        --shadow-color: rgba(59,130,246,0.13);
        transition: box-shadow 0.25s, transform 0.18s, border-color 0.18s;
        background: #fff;
        border-radius: 1.2rem;
        border: 2px solid #e5e7eb;
        position: relative;
        min-height: 320px;
        max-width: 320px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        margin-bottom: 1rem;
        padding-top: 1.2rem;
        box-shadow: 0 2px 12px 0 var(--shadow-color);
    }
    .product-card:hover {
        box-shadow: 0 8px 20px 0 var(--shadow-color, rgba(59,130,246,0.18));
        transform: translateY(-4px) scale(1.025);
        border-color: var(--accent-color, #3b82f6);
        background: #f9fafb;
    }
    .product-img {
        border-radius: 50%;
        border: 3px solid var(--accent-color, #3b82f6);
        width: 140px;
        height: 140px;
        object-fit: cover;
        background: #fff;
        margin-bottom: 0.7rem;
        box-shadow: 0 3px 12px 0 var(--shadow-color);
        transition: box-shadow 0.2s, border-color 0.2s;
        z-index: 2;
    }
    .product-card:hover .product-img {
        border-color: var(--accent-color, #3b82f6);
        box-shadow: 0 8px 20px 0 var(--shadow-color, rgba(59,130,246,0.18));
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
        box-shadow: 0 1px 4px 0 var(--shadow-color, rgba(59,130,246,0.13));
        letter-spacing: 0.07em;
        text-shadow: 0 1px 2px rgba(0,0,0,0.08);
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
        box-shadow: 0 2px 8px 0 var(--shadow-color, rgba(59,130,246,0.13));
        transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.7rem;
    }
    .add-to-cart-btn:hover {
        background: #111827;
        color: #fff;
        box-shadow: 0 4px 16px 0 var(--shadow-color, rgba(59,130,246,0.22));
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
            <h3 class="product-title">{{ $product->name }}</h3>
            @if(!empty($product->statement))
                <p class="text-sm text-gray-500 mb-1 italic">{{ $product->statement }}</p>
            @endif
            @if(!empty($product->rating))
                <div class="flex items-center mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($product->rating))
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.6 1.6,7.3 6.1,11.3 4.8,17.2 9.9,14.2 15,17.2 13.7,11.3 18.2,7.3 12.2,6.6 "/></svg>
                        @elseif($i - $product->rating < 1)
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><defs><linearGradient id="half"><stop offset="50%" stop-color="#facc15"/><stop offset="50%" stop-color="#d1d5db"/></linearGradient></defs><polygon points="9.9,1.1 7.6,6.6 1.6,7.3 6.1,11.3 4.8,17.2 9.9,14.2 15,17.2 13.7,11.3 18.2,7.3 12.2,6.6 " fill="url(#half)"/></svg>
                        @else
                            <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.6 1.6,7.3 6.1,11.3 4.8,17.2 9.9,14.2 15,17.2 13.7,11.3 18.2,7.3 12.2,6.6 "/></svg>
                        @endif
                    @endfor
                    <span class="ml-2 text-xs text-gray-500">{{ number_format($product->rating, 1) }}</span>
                </div>
            @endif
            <p class="mb-1 text-gray-500 text-base">Available: <span class="product-available">{{ $product->available }} {{ $product->unit }}</span></p>
            <p class="product-price">$ {{ number_format($product->unit_price, 2) }}</p>
            @if($product->available > 0 && $product->inventory_id)
                <form action="{{ route('customer.cart.add') }}" method="POST" class="flex flex-col items-center w-full">
                    @csrf
                    <input type="hidden" name="inventory_id" value="{{ $product->inventory_id }}">
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->available }}" class="quantity-input">
                    <button type="submit" class="add-to-cart-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01" /></svg>
                        Add to Cart
                    </button>
                </form>
            @else
                <span class="out-of-stock">Out of Stock</span>
            @endif
        </div>
    @empty
        <div class="col-span-3 text-center text-gray-500">No products available.</div>
    @endforelse
</div>
@endsection
