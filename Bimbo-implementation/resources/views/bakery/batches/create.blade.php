@extends('layouts.bakery-manager')

@section('header')
New Production Batch
@endsection

@section('content')
<div class="max-w-xl mx-auto">
    <form method="POST" action="{{ route('bakery.batches.store') }}">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
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
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Batch</button>
        <a href="{{ route('bakery.batches.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection