@extends('layouts.bakery-manager')

@section('header')
Edit Maintenance Task
@endsection

@section('content')
<div class="max-w-xl mx-auto">
    <form method="POST" action="{{ route('bakery.maintenance-tasks.update', $maintenanceTask) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="machine_id" class="block text-sm font-medium text-gray-700">Machine</label>
            <select name="machine_id" id="machine_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">Select Machine</option>
                @foreach($machines as $machine)
                <option value="{{ $machine->id }}" {{ old('machine_id', $maintenanceTask->machine_id) == $machine->id ? ' selected' : '' }}>{{ $machine->name }}</option>
                @endforeach
            </select>
            @error('machine_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="scheduled_for_date" class="block text-sm font-medium text-gray-700">Scheduled For Date</label>
            <input type="date" name="scheduled_for_date" id="scheduled_for_date" value="{{ old('scheduled_for_date', $maintenanceTask->scheduled_for ? date('Y-m-d', strtotime($maintenanceTask->scheduled_for)) : '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label for="scheduled_for_time_raw" class="block text-sm font-medium text-gray-700">Scheduled For Time</label>
            <div class="flex space-x-2">
                <input type="time" name="scheduled_for_time_raw" id="scheduled_for_time_raw" value="{{ old('scheduled_for_time_raw', $maintenanceTask->scheduled_for ? date('h:i', strtotime($maintenanceTask->scheduled_for)) : '') }}" required class="block w-full rounded-md border-gray-300 shadow-sm">
                <select name="scheduled_for_time_ampm" id="scheduled_for_time_ampm" required class="block rounded-md border-gray-300 shadow-sm">
                    <option value="AM" {{ old('scheduled_for_time_ampm', $maintenanceTask->scheduled_for && date('A', strtotime($maintenanceTask->scheduled_for)) == 'AM' ? 'AM' : '') == 'AM' ? ' selected' : '' }}>AM</option>
                    <option value="PM" {{ old('scheduled_for_time_ampm', $maintenanceTask->scheduled_for && date('A', strtotime($maintenanceTask->scheduled_for)) == 'PM' ? 'PM' : '') == 'PM' ? ' selected' : '' }}>PM</option>
                </select>
            </div>
            <input type="hidden" name="scheduled_for" id="scheduled_for">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $maintenanceTask->description) }}</textarea>
            @error('description')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="scheduled" {{ old('status', $maintenanceTask->status) == 'scheduled' ? ' selected' : '' }}>Scheduled</option>
                <option value="completed" {{ old('status', $maintenanceTask->status) == 'completed' ? ' selected' : '' }}>Completed</option>
                <option value="overdue" {{ old('status', $maintenanceTask->status) == 'overdue' ? ' selected' : '' }}>Overdue</option>
            </select>
            @error('status')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="completed_at_date" class="block text-sm font-medium text-gray-700">Completed At Date</label>
            <input type="date" name="completed_at_date" id="completed_at_date" value="{{ old('completed_at_date', $maintenanceTask->completed_at ? date('Y-m-d', strtotime($maintenanceTask->completed_at)) : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label for="completed_at_time_raw" class="block text-sm font-medium text-gray-700">Completed At Time</label>
            <div class="flex space-x-2">
                <input type="time" name="completed_at_time_raw" id="completed_at_time_raw" value="{{ old('completed_at_time_raw', $maintenanceTask->completed_at ? date('h:i', strtotime($maintenanceTask->completed_at)) : '') }}" class="block w-full rounded-md border-gray-300 shadow-sm">
                <select name="completed_at_time_ampm" id="completed_at_time_ampm" class="block rounded-md border-gray-300 shadow-sm">
                    <option value="AM" {{ old('completed_at_time_ampm', $maintenanceTask->completed_at && date('A', strtotime($maintenanceTask->completed_at)) == 'AM' ? 'AM' : '') == 'AM' ? ' selected' : '' }}>AM</option>
                    <option value="PM" {{ old('completed_at_time_ampm', $maintenanceTask->completed_at && date('A', strtotime($maintenanceTask->completed_at)) == 'PM' ? 'PM' : '') == 'PM' ? ' selected' : '' }}>PM</option>
                </select>
            </div>
            <input type="hidden" name="completed_at" id="completed_at">
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Task</button>
        <a href="{{ route('bakery.maintenance-tasks.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
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
        // Scheduled For
        const sfd = document.getElementById('scheduled_for_date').value;
        const sft = document.getElementById('scheduled_for_time_raw').value;
        const sfampm = document.getElementById('scheduled_for_time_ampm').value;
        document.getElementById('scheduled_for').value = sfd + 'T' + to24(sft, sfampm);
        // Completed At
        const cad = document.getElementById('completed_at_date').value;
        const cat = document.getElementById('completed_at_time_raw').value;
        const caampm = document.getElementById('completed_at_time_ampm').value;
        if (cad && cat && caampm) {
            document.getElementById('completed_at').value = cad + 'T' + to24(cat, caampm);
        }
    });
</script>
@endsection