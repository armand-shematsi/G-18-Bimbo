@extends('layouts.bakery-manager')

@section('header')
    Schedule Maintenance Task
@endsection

@section('content')
    <div class="max-w-xl mx-auto">
        <form method="POST" action="{{ route('bakery.maintenance-tasks.store') }}">
            @csrf
            <div class="mb-4">
                <label for="machine_id" class="block text-sm font-medium text-gray-700">Machine</label>
                <select name="machine_id" id="machine_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Select Machine</option>
                    @foreach($machines as $machine)
                        <option value="{{ $machine->id }}"{{ old('machine_id') == $machine->id ? ' selected' : '' }}>{{ $machine->name }}</option>
                    @endforeach
                </select>
                @error('machine_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="scheduled_for" class="block text-sm font-medium text-gray-700">Scheduled For</label>
                <input type="datetime-local" name="scheduled_for" id="scheduled_for" value="{{ old('scheduled_for') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('scheduled_for')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description') }}</textarea>
                @error('description')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="scheduled"{{ old('status') == 'scheduled' ? ' selected' : '' }}>Scheduled</option>
                    <option value="completed"{{ old('status') == 'completed' ? ' selected' : '' }}>Completed</option>
                    <option value="overdue"{{ old('status') == 'overdue' ? ' selected' : '' }}>Overdue</option>
                </select>
                @error('status')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="completed_at" class="block text-sm font-medium text-gray-700">Completed At</label>
                <input type="datetime-local" name="completed_at" id="completed_at" value="{{ old('completed_at') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('completed_at')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Schedule Task</button>
            <a href="{{ route('bakery.maintenance-tasks.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
        </form>
    </div>
@endsection 