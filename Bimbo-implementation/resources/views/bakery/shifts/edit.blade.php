@extends('layouts.bakery-manager')

@section('header')
    Edit Shift
@endsection

@section('content')
    <div class="max-w-xl mx-auto">
        <form method="POST" action="{{ route('bakery.shifts.update', $shift) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="user_id" class="block text-sm font-medium text-gray-700">Staff</label>
                <select name="user_id" id="user_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Select Staff</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"{{ old('user_id', $shift->user_id) == $user->id ? ' selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('user_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <input type="text" name="role" id="role" value="{{ old('role', $shift->role) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('role')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="production_batch_id" class="block text-sm font-medium text-gray-700">Batch</label>
                <select name="production_batch_id" id="production_batch_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">None</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}"{{ old('production_batch_id', $shift->production_batch_id) == $batch->id ? ' selected' : '' }}>{{ $batch->name }}</option>
                    @endforeach
                </select>
                @error('production_batch_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time', $shift->start_time ? date('Y-m-d\TH:i', strtotime($shift->start_time)) : '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('start_time')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time', $shift->end_time ? date('Y-m-d\TH:i', strtotime($shift->end_time)) : '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('end_time')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Shift</button>
            <a href="{{ route('bakery.shifts.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
        </form>
    </div>
@endsection 