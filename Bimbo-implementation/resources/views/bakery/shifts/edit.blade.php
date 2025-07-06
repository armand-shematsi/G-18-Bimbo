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
                <option value="{{ $user->id }}" {{ old('user_id', $shift->user_id) == $user->id ? ' selected' : '' }}>{{ $user->name }}</option>
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
                <option value="{{ $batch->id }}" {{ old('production_batch_id', $shift->production_batch_id) == $batch->id ? ' selected' : '' }}>{{ $batch->name }}</option>
                @endforeach
            </select>
            @error('production_batch_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $shift->start_time ? date('Y-m-d', strtotime($shift->start_time)) : '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @error('start_date')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
            <div class="flex space-x-2">
                <input type="time" name="start_time_raw" id="start_time_raw" value="{{ old('start_time_raw', $shift->start_time ? date('h:i', strtotime($shift->start_time)) : '') }}" required class="block w-full rounded-md border-gray-300 shadow-sm">
                <select name="start_time_ampm" id="start_time_ampm" required class="block rounded-md border-gray-300 shadow-sm">
                    <option value="AM" {{ old('start_time_ampm', $shift->start_time && date('A', strtotime($shift->start_time)) == 'AM' ? 'AM' : '') == 'AM' ? ' selected' : '' }}>AM</option>
                    <option value="PM" {{ old('start_time_ampm', $shift->start_time && date('A', strtotime($shift->start_time)) == 'PM' ? 'PM' : '') == 'PM' ? ' selected' : '' }}>PM</option>
                </select>
            </div>
            <input type="hidden" name="start_time" id="start_time" value="">
            @error('start_time')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $shift->end_time ? date('Y-m-d', strtotime($shift->end_time)) : '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @error('end_date')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
            <div class="flex space-x-2">
                <input type="time" name="end_time_raw" id="end_time_raw" value="{{ old('end_time_raw', $shift->end_time ? date('h:i', strtotime($shift->end_time)) : '') }}" required class="block w-full rounded-md border-gray-300 shadow-sm">
                <select name="end_time_ampm" id="end_time_ampm" required class="block rounded-md border-gray-300 shadow-sm">
                    <option value="AM" {{ old('end_time_ampm', $shift->end_time && date('A', strtotime($shift->end_time)) == 'AM' ? 'AM' : '') == 'AM' ? ' selected' : '' }}>AM</option>
                    <option value="PM" {{ old('end_time_ampm', $shift->end_time && date('A', strtotime($shift->end_time)) == 'PM' ? 'PM' : '') == 'PM' ? ' selected' : '' }}>PM</option>
                </select>
            </div>
            <input type="hidden" name="end_time" id="end_time" value="">
            @error('end_time')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Shift</button>
        <a href="{{ route('bakery.shifts.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        // Start time
        const sd = document.getElementById('start_date').value;
        let st = document.getElementById('start_time_raw').value;
        const stam = document.getElementById('start_time_ampm').value;
        // End time
        const ed = document.getElementById('end_date').value;
        let et = document.getElementById('end_time_raw').value;
        const etam = document.getElementById('end_time_ampm').value;
        // Convert to 24-hour
        function to24(time, ampm) {
            let [h, m] = time.split(':');
            h = parseInt(h);
            if (ampm === 'PM' && h < 12) h += 12;
            if (ampm === 'AM' && h === 12) h = 0;
            return (h < 10 ? '0' : '') + h + ':' + m;
        }
        document.getElementById('start_time').value = sd + 'T' + to24(st, stam);
        document.getElementById('end_time').value = ed + 'T' + to24(et, etam);
    });
</script>
@endsection