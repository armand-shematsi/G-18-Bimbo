@extends('layouts.retail-manager')

@section('header')
    <h2 class="text-2xl font-extrabold text-blue-800 mb-6 flex items-center gap-2">
        <svg class="w-7 h-7 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/></svg>
        Bread Products
    </h2>
@endsection

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
    @foreach($products as $product)
        <div class="bg-gradient-to-br from-red-200 to-pink-300 rounded-2xl shadow-lg overflow-hidden flex flex-col transition-transform hover:scale-105 hover:shadow-2xl border border-gray-100">
            <div class="h-48 w-full bg-gray-50 flex items-center justify-center overflow-hidden">
                <img src="{{ $product->image_url ? asset($product->image_url) : asset('images/' . strtolower(str_replace(' ', '_', $product->name)) . '.jpg') }}"
                     alt="{{ $product->name }}"
                     class="object-cover h-44 w-44 rounded-full border-4 border-white shadow-md"
                     onerror="this.onerror=null;this.src='https://via.placeholder.com/180?text=No+Image';">
            </div>
            <div class="p-6 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-extrabold text-gray-900 mb-1">{{ $product->name }}</h3>
                    <div class="flex items-center mb-2">
                        <span class="flex text-yellow-400 mr-2">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><polygon points="10 1.5 12.59 7.36 18.82 7.63 13.97 11.64 15.54 17.77 10 14.27 4.46 17.77 6.03 11.64 1.18 7.63 7.41 7.36 10 1.5"/></svg>
                            @endfor
                        </span>
                        <span class="text-xs text-gray-500">5.0</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-3">Delicious, fresh, and perfect for any meal. Try our {{ strtolower($product->name) }} today!</p>
                    <p class="text-primary font-semibold text-lg mb-4">UGX {{ number_format($product->unit_price, 0) }}</p>
                </div>
                <form action="{{ route('retail.cart.add') }}" method="POST" class="mt-auto">
                    @csrf
                    <input type="hidden" name="inventory_id" value="{{ $product->inventory_id }}">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="product_name" value="{{ $product->name }}">
                    <input type="hidden" name="unit_price" value="{{ $product->unit_price }}">
                    <div class="flex items-center mb-3">
                        <label for="quantity-{{ $product->id }}" class="mr-2 font-medium text-gray-700">Qty:</label>
                        <input type="number" name="quantity" id="quantity-{{ $product->id }}" value="1" min="1" class="w-20 border border-gray-300 rounded-lg px-2 py-1 focus:ring-2 focus:ring-primary focus:border-primary transition">
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white font-bold py-2 px-4 rounded-lg shadow transition-all duration-150 transform hover:scale-105">Add to Cart</button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection
