@if(isset($products) && $products->count() > 0)
<div class="mt-8 bg-white rounded-xl shadow-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Our Fresh Bread Products</h3>
        <p class="text-sm text-gray-600 mt-1">Discover our delicious selection of freshly baked bread</p>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                <div class="relative">
                    @php
                        $imageName = strtolower(str_replace(' ', '_', $product->name)) . '.jpg';
                    @endphp
                    <img src="{{ asset('images/' . $imageName) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-48 object-cover rounded-t-xl"
                         onerror="this.src='{{ asset('images/k-Photo-Recipe Ramp Up-2021-11-Potato-Bread-potato_bread_01.jpeg') }}'">
                    <div class="absolute top-2 right-2">
                        <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">Fresh</span>
                    </div>
                </div>
                <div class="p-4">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $product->name }}</h4>
                    <p class="text-sm text-gray-600 mb-3">{{ $product->description ?? 'Delicious freshly baked bread made with premium ingredients.' }}</p>
                    
                    <!-- Star Rating -->
                    <div class="flex items-center mb-3">
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-xs text-gray-500 ml-2">(4.8)</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-xl font-bold text-green-600">₦{{ number_format($product->price, 2) }}</span>
                        <form action="{{ route('retail.cart.add') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="inventory_id" value="{{ $product->inventory_id }}">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="product_name" value="{{ $product->name }}">
                            <input type="hidden" name="unit_price" value="{{ $product->unit_price ?? $product->price ?? 0 }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" 
                                    class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:from-green-600 hover:to-green-700 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                                </svg>
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif 