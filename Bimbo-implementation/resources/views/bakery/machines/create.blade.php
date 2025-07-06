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
            <div>
                <label for="last_maintenance_at_date" class="block text-sm font-medium text-gray-700">Last Maintenance Date</label>
                <input type="date" name="last_maintenance_at_date" id="last_maintenance_at_date" value="{{ old('last_maintenance_at_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label for="last_maintenance_at_time_raw" class="block text-sm font-medium text-gray-700">Last Maintenance Time</label>
                <div class="flex space-x-2">
                    <input type="time" name="last_maintenance_at_time_raw" id="last_maintenance_at_time_raw" value="{{ old('last_maintenance_at_time_raw') }}" class="block w-full rounded-md border-gray-300 shadow-sm">
                    <select name="last_maintenance_at_time_ampm" id="last_maintenance_at_time_ampm" class="block rounded-md border-gray-300 shadow-sm">
                        <option value="AM"{{ old('last_maintenance_at_time_ampm') == 'AM' ? ' selected' : '' }}>AM</option>
                        <option value="PM"{{ old('last_maintenance_at_time_ampm') == 'PM' ? ' selected' : '' }}>PM</option>
                    </select>
                </div>
                <input type="hidden" name="last_maintenance_at" id="last_maintenance_at">
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
    <script>
    document.querySelector('form').addEventListener('submit', function(e) {
        function to24(time, ampm) {
            let [h, m] = time.split(':');
            h = parseInt(h);
            if (ampm === 'PM' && h < 12) h += 12;
            if (ampm === 'AM' && h === 12) h = 0;
            return (h < 10 ? '0' : '') + h + ':' + m;
        }
        const d = document.getElementById('last_maintenance_at_date').value;
        const t = document.getElementById('last_maintenance_at_time_raw').value;
        const ampm = document.getElementById('last_maintenance_at_time_ampm').value;
        if (d && t && ampm) {
            document.getElementById('last_maintenance_at').value = d + 'T' + to24(t, ampm);
        }
    });
    </script>
@endsection 