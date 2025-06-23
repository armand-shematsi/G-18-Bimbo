@extends('layouts.bakery-manager')

@section('header')
    Edit Production Batch
@endsection

@section('content')
    <div class="max-w-xl mx-auto">
        <form method="POST" action="{{ route('bakery.batches.update', $batch) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $batch->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="planned"{{ old('status', $batch->status) == 'planned' ? ' selected' : '' }}>Planned</option>
                    <option value="active"{{ old('status', $batch->status) == 'active' ? ' selected' : '' }}>Active</option>
                    <option value="completed"{{ old('status', $batch->status) == 'completed' ? ' selected' : '' }}>Completed</option>
                    <option value="cancelled"{{ old('status', $batch->status) == 'cancelled' ? ' selected' : '' }}>Cancelled</option>
                </select>
                @error('status')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="scheduled_start" class="block text-sm font-medium text-gray-700">Scheduled Start</label>
                <input type="datetime-local" name="scheduled_start" id="scheduled_start" value="{{ old('scheduled_start', $batch->scheduled_start ? date('Y-m-d\TH:i', strtotime($batch->scheduled_start)) : '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('scheduled_start')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="actual_start" class="block text-sm font-medium text-gray-700">Actual Start</label>
                <input type="datetime-local" name="actual_start" id="actual_start" value="{{ old('actual_start', $batch->actual_start ? date('Y-m-d\TH:i', strtotime($batch->actual_start)) : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('actual_start')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="actual_end" class="block text-sm font-medium text-gray-700">Actual End</label>
                <input type="datetime-local" name="actual_end" id="actual_end" value="{{ old('actual_end', $batch->actual_end ? date('Y-m-d\TH:i', strtotime($batch->actual_end)) : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('actual_end')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" id="notes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('notes', $batch->notes) }}</textarea>
                @error('notes')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Batch</button>
            <a href="{{ route('bakery.batches.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
        </form>
    </div>
@endsection 