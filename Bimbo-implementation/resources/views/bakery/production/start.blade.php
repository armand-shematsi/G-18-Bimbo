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
                        <select name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Select Batch Name</option>
                            @foreach($products as $product)
                            <option value="{{ $product->name }}" {{ old('name') == $product->name ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
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
                        <label for="scheduled_start_date" class="block text-sm font-medium text-gray-700">Scheduled Start Date</label>
                        <input type="date" name="scheduled_start_date" id="scheduled_start_date" value="{{ old('scheduled_start_date') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label for="scheduled_start_time_raw" class="block text-sm font-medium text-gray-700">Scheduled Start Time</label>
                        <div class="flex space-x-2">
                            <input type="time" name="scheduled_start_time_raw" id="scheduled_start_time_raw" value="{{ old('scheduled_start_time_raw') }}" required class="block w-full rounded-md border-gray-300 shadow-sm">
                            <select name="scheduled_start_time_ampm" id="scheduled_start_time_ampm" required class="block rounded-md border-gray-300 shadow-sm">
                                <option value="AM" {{ old('scheduled_start_time_ampm') == 'AM' ? ' selected' : '' }}>AM</option>
                                <option value="PM" {{ old('scheduled_start_time_ampm') == 'PM' ? ' selected' : '' }}>PM</option>
                            </select>
                        </div>
                        <input type="hidden" name="scheduled_start" id="scheduled_start">
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
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        const sd = document.getElementById('scheduled_start_date').value;
        let st = document.getElementById('scheduled_start_time_raw').value;
        const stam = document.getElementById('scheduled_start_time_ampm').value;

        function to24(time, ampm) {
            let [h, m] = time.split(':');
            h = parseInt(h);
            if (ampm === 'PM' && h < 12) h += 12;
            if (ampm === 'AM' && h === 12) h = 0;
            return (h < 10 ? '0' : '') + h + ':' + m;
        }
        document.getElementById('scheduled_start').value = sd + 'T' + to24(st, stam);
    });
</script>
@endsection