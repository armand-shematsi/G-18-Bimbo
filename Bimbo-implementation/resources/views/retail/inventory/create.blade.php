@extends('layouts.retail-manager')

@section('header')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Add Inventory Item</h1>
    <p class="text-sm text-gray-500">Add a new item to the retail shop inventory</p>
</div>
@endsection

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow">
    @if($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('retail.inventory.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
            <select name="product_id" id="product_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">Select a product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
            <input type="number" name="quantity" min="0" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price</label>
            <input type="number" name="unit_price" min="0" step="0.01" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
            <input type="text" name="unit" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Item Type (optional)</label>
            <input type="text" name="item_type" class="w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Reorder Level</label>
            <input type="number" name="reorder_level" min="0" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>
        <div class="flex justify-end">
            <a href="{{ route('retail.inventory.check') }}" class="mr-4 text-gray-600 hover:underline">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Item</button>
        </div>
    </form>
</div>
@endsection
