@extends('layouts.bakery-manager')

@section('header')
Start New Production
@endsection

@section('content')
<div class="max-w-xl mx-auto">
    <form method="POST" action="{{ route('bakery.batches.store') }}">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <select name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">Select batch name</option>
                <option value="White Bread">White Bread</option>
                <option value="Brown Bread">Brown Bread</option>
                <option value="Baguette">Baguette</option>
                <option value="Ciabatta">Ciabatta</option>
                <option value="Rye Bread">Rye Bread</option>
                <option value="Multigrain">Multigrain</option>
                <option value="Sourdough">Sourdough</option>
                <option value="Brioche">Brioche</option>
            </select>
            @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="scheduled_start" class="block text-sm font-medium text-gray-700">Scheduled Start</label>
            <input type="datetime-local" name="scheduled_start" id="scheduled_start" value="{{ old('scheduled_start') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @error('scheduled_start')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="planned" {{ old('status') == 'planned' ? ' selected' : '' }}>Planned</option>
                <option value="active" {{ old('status') == 'active' ? ' selected' : '' }}>Active</option>
                <option value="completed" {{ old('status') == 'completed' ? ' selected' : '' }}>Completed</option>
                <option value="cancelled" {{ old('status') == 'cancelled' ? ' selected' : '' }}>Cancelled</option>
            </select>
            @error('status')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity (Loaves Produced)</label>
            <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @error('quantity')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="production_line_id" class="block text-sm font-medium text-gray-700">Production Line</label>
            <select name="production_line_id" id="production_line_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">Select a line</option>
                <option value="A">Line A</option>
                <option value="B">Line B</option>
                <option value="C">Line C</option>
                <option value="D">Line D</option>
                <option value="E">Line E</option>
                <option value="F">Line F</option>
                <option value="G">Line G</option>
                <option value="H">Line H</option>
            </select>
            @error('production_line_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="staff" class="block text-sm font-medium text-gray-700">Assign Staff</label>
            <select name="staff[]" id="staff" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @foreach($staff as $user)
                <option value="{{ $user->id }}" {{ (collect(old('staff'))->contains($user->id)) ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
            <small class="text-gray-500">Hold Ctrl (Windows) or Cmd (Mac) to select multiple staff.</small>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Ingredients Used</label>
            <div class="space-y-2">
                @foreach($ingredients as $ingredient)
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="ingredient_{{ $ingredient->id }}" name="ingredients[{{ $ingredient->id }}][id]" value="{{ $ingredient->id }}" {{ old('ingredients.'.$ingredient->id.'.id') ? 'checked' : '' }}>
                    <label for="ingredient_{{ $ingredient->id }}" class="flex-1">{{ $ingredient->name }} ({{ $ingredient->unit }})</label>
                    <input type="number" step="0.01" min="0" name="ingredients[{{ $ingredient->id }}][quantity]" value="{{ old('ingredients.'.$ingredient->id.'.quantity') }}" placeholder="Qty" class="w-24 rounded-md border-gray-300 shadow-sm">
                </div>
                @endforeach
            </div>
            @error('ingredients')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Start Production</button>
        <a href="{{ route('bakery.batches.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection