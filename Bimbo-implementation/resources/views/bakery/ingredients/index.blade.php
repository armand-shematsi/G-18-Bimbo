@extends('layouts.bakery-manager')

@section('header')
    Ingredients
@endsection

@section('content')
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-semibold">Ingredients</h2>
        <a href="{{ route('bakery.ingredients.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Ingredient</a>
    </div>
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Unit</th>
                    <th class="px-4 py-2">Stock</th>
                    <th class="px-4 py-2">Low Stock Threshold</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ingredients as $ingredient)
                    <tr>
                        <td class="border px-4 py-2">{{ $ingredient->name }}</td>
                        <td class="border px-4 py-2">{{ $ingredient->unit }}</td>
                        <td class="border px-4 py-2">{{ $ingredient->stock_quantity }}</td>
                        <td class="border px-4 py-2">{{ $ingredient->low_stock_threshold ?? '-' }}</td>
                        <td class="border px-4 py-2 flex space-x-2">
                            <a href="{{ route('bakery.ingredients.show', $ingredient) }}" class="text-blue-600 hover:underline">View</a>
                            <a href="{{ route('bakery.ingredients.edit', $ingredient) }}" class="text-yellow-600 hover:underline">Edit</a>
                            <form action="{{ route('bakery.ingredients.destroy', $ingredient) }}" method="POST" onsubmit="return confirm('Delete this ingredient?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $ingredients->links() }}
    </div>
@endsection 