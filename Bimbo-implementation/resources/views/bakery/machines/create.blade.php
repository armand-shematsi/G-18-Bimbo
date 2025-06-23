@extends('layouts.bakery-manager')

@section('header')
    Add Machine
@endsection

@section('content')
    <div class="max-w-xl mx-auto">
        <form method="POST" action="{{ route('bakery.machines.store') }}">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                <input type="text" name="type" id="type" value="{{ old('type') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('type')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="operational"{{ old('status') == 'operational' ? ' selected' : '' }}>Operational</option>
                    <option value="maintenance"{{ old('status') == 'maintenance' ? ' selected' : '' }}>Maintenance</option>
                    <option value="down"{{ old('status') == 'down' ? ' selected' : '' }}>Down</option>
                </select>
                @error('status')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="last_maintenance_at" class="block text-sm font-medium text-gray-700">Last Maintenance</label>
                <input type="datetime-local" name="last_maintenance_at" id="last_maintenance_at" value="{{ old('last_maintenance_at') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('last_maintenance_at')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" id="notes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('notes') }}</textarea>
                @error('notes')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Machine</button>
            <a href="{{ route('bakery.machines.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
        </form>
    </div>
@endsection 