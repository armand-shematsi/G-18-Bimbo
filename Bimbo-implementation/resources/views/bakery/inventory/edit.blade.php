@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center">
                <a href="{{ route('bakery.inventory.show', $inventory) }}" class="text-blue-600 hover:text-blue-800 mr-4">
                    <i class="fas fa-arrow-left"></i> Back to Item
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit {{ $inventory->item_name }}</h1>
                    <p class="text-gray-600 mt-2">Update inventory item information</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('bakery.inventory.update', $inventory) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Item Name -->
                    <div class="md:col-span-2">
                        <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">Item Name *</label>
                        <input type="text"
                               name="item_name"
                               id="item_name"
                               value="{{ old('item_name', $inventory->item_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('item_name') border-red-500 @enderror"
                               placeholder="e.g., All-purpose flour">
                        @error('item_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Item Type -->
                    <div>
                        <label for="item_type" class="block text-sm font-medium text-gray-700 mb-2">Item Type *</label>
                        <select name="item_type"
                                id="item_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('item_type') border-red-500 @enderror">
                            <option value="">Select Type</option>
                            @foreach($itemTypes as $type)
                                <option value="{{ $type }}" {{ old('item_type', $inventory->item_type) == $type ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('item_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product -->
                    <div>
                        <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">Associated Product</label>
                        <select name="product_id"
                                id="product_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('product_id') border-red-500 @enderror">
                            <option value="">Select Product (Optional)</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id', $inventory->product_id) == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Current Quantity *</label>
                        <input type="number"
                               name="quantity"
                               id="quantity"
                               value="{{ old('quantity', $inventory->quantity) }}"
                               min="0"
                               step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('quantity') border-red-500 @enderror"
                               placeholder="0">
                        <p class="mt-1 text-xs text-gray-500">Note: Use "Update Stock" for inventory movements</p>
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit -->
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">Unit *</label>
                        <input type="text"
                               name="unit"
                               id="unit"
                               value="{{ old('unit', $inventory->unit) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('unit') border-red-500 @enderror"
                               placeholder="e.g., kg, pieces, liters">
                        @error('unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit Price -->
                    <div>
                        <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-2">Unit Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number"
                                   name="unit_price"
                                   id="unit_price"
                                   value="{{ old('unit_price', $inventory->unit_price) }}"
                                   min="0"
                                   step="0.01"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('unit_price') border-red-500 @enderror"
                                   placeholder="0.00">
                        </div>
                        @error('unit_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reorder Level -->
                    <div>
                        <label for="reorder_level" class="block text-sm font-medium text-gray-700 mb-2">Reorder Level *</label>
                        <input type="number"
                               name="reorder_level"
                               id="reorder_level"
                               value="{{ old('reorder_level', $inventory->reorder_level) }}"
                               min="0"
                               step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('reorder_level') border-red-500 @enderror"
                               placeholder="0">
                        @error('reorder_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="md:col-span-2">
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Storage Location</label>
                        <input type="text"
                               name="location"
                               id="location"
                               value="{{ old('location', $inventory->location) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('location') border-red-500 @enderror"
                               placeholder="e.g., Warehouse A, Shelf 3, Refrigerator">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Current Status Display -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Current Status</h3>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">Stock Level:</span>
                        <span class="text-sm font-medium">{{ $inventory->quantity }} {{ $inventory->unit }}</span>

                        <span class="text-sm text-gray-600">Status:</span>
                        @if($inventory->quantity == 0)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Out of Stock
                            </span>
                        @elseif($inventory->quantity <= $inventory->reorder_level)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Low Stock
                            </span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Available
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('bakery.inventory.show', $inventory) }}"
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                        Update Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
