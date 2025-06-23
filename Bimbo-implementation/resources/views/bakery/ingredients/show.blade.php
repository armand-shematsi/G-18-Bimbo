@extends('layouts.bakery-manager')

@section('header')
    Ingredient Details
@endsection

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('bakery.ingredients.index') }}" class="text-blue-600 hover:underline">&larr; Back to Ingredients</a>
        </div>
        <div class="bg-white shadow rounded p-6">
            <h2 class="text-lg font-semibold mb-4">{{ $ingredient->name }}</h2>
            <div class="mb-2"><strong>Unit:</strong> {{ $ingredient->unit }}</div>
            <div class="mb-2"><strong>Stock Quantity:</strong> {{ $ingredient->stock_quantity }}</div>
            <div class="mb-2"><strong>Low Stock Threshold:</strong> {{ $ingredient->low_stock_threshold ?? '-' }}</div>
        </div>
        <div class="mt-4 flex space-x-2">
            <a href="{{ route('bakery.ingredients.edit', $ingredient) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
            <form action="{{ route('bakery.ingredients.destroy', $ingredient) }}" method="POST" onsubmit="return confirm('Delete this ingredient?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
            </form>
        </div>
    </div>
@endsection 