@extends('layouts.bakery-manager')

@section('header')
    Start New Production
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <form method="POST" action="{{ route('bakery.production.store') }}" class="space-y-6">
                    @csrf
                    <!-- Batch Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Batch Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <!-- Production Line -->
                    <div>
                        <label for="line" class="block text-sm font-medium text-gray-700">Production Line</label>
                        <input type="text" name="line" id="line" value="{{ old('line') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('line')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <!-- Scheduled Start -->
                    <div>
                        <label for="scheduled_start" class="block text-sm font-medium text-gray-700">Scheduled Start</label>
                        <input type="datetime-local" name="scheduled_start" id="scheduled_start" value="{{ old('scheduled_start') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('scheduled_start')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('quantity')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <!-- Ingredients -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ingredients Used</label>
                        <div class="space-y-2">
                            @foreach($ingredients as $ingredient)
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" id="ingredient_{{ $ingredient->id }}" name="ingredients[{{ $loop->index }}][id]" value="{{ $ingredient->id }}" {{ old('ingredients.'.$loop->index.'.id') == $ingredient->id ? 'checked' : '' }}>
                                    <label for="ingredient_{{ $ingredient->id }}" class="flex-1">{{ $ingredient->name }} ({{ $ingredient->unit }})</label>
                                    <input type="number" step="0.01" min="0" name="ingredients[{{ $loop->index }}][quantity]" value="{{ old('ingredients.'.$loop->index.'.quantity') }}" placeholder="Qty" class="w-24 rounded-md border-gray-300 shadow-sm">
                                </div>
                            @endforeach
                        </div>
                        @error('ingredients')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('notes') }}</textarea>
                        @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Start Production
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 