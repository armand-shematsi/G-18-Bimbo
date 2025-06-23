@extends('layouts.bakery-manager')

@section('header')
    Add Ingredient
@endsection

@section('content')
    <div class="max-w-xl mx-auto">
        <form method="POST" action="{{ route('bakery.ingredients.store') }}">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="unit" class="block text-sm font-medium text-gray-700">Unit</label>
                <input type="text" name="unit" id="unit" value="{{ old('unit') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('unit')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                <input type="number" step="0.01" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('stock_quantity')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700">Low Stock Threshold</label>
                <input type="number" step="0.01" name="low_stock_threshold" id="low_stock_threshold" value="{{ old('low_stock_threshold') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('low_stock_threshold')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Ingredient</button>
            <a href="{{ route('bakery.ingredients.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
        </form>
    </div>
@endsection 