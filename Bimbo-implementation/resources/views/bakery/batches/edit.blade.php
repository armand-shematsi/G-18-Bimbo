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
                <option value="planned" {{ old('status', $batch->status) == 'planned' ? ' selected' : '' }}>Planned</option>
                <option value="active" {{ old('status', $batch->status) == 'active' ? ' selected' : '' }}>Active</option>
                <option value="completed" {{ old('status', $batch->status) == 'completed' ? ' selected' : '' }}>Completed</option>
                <option value="cancelled" {{ old('status', $batch->status) == 'cancelled' ? ' selected' : '' }}>Cancelled</option>
            </select>
            @error('status')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="scheduled_start_date" class="block text-sm font-medium text-gray-700">Scheduled Start Date</label>
            <input type="date" name="scheduled_start_date" id="scheduled_start_date" value="{{ old('scheduled_start_date', $batch->scheduled_start ? date('Y-m-d', strtotime($batch->scheduled_start)) : '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label for="scheduled_start_time_raw" class="block text-sm font-medium text-gray-700">Scheduled Start Time</label>
            <div class="flex space-x-2">
                <input type="time" name="scheduled_start_time_raw" id="scheduled_start_time_raw" value="{{ old('scheduled_start_time_raw', $batch->scheduled_start ? date('h:i', strtotime($batch->scheduled_start)) : '') }}" required class="block w-full rounded-md border-gray-300 shadow-sm">
                <select name="scheduled_start_time_ampm" id="scheduled_start_time_ampm" required class="block rounded-md border-gray-300 shadow-sm">
                    <option value="AM" {{ old('scheduled_start_time_ampm', $batch->scheduled_start && date('A', strtotime($batch->scheduled_start)) == 'AM' ? 'AM' : '') == 'AM' ? ' selected' : '' }}>AM</option>
                    <option value="PM" {{ old('scheduled_start_time_ampm', $batch->scheduled_start && date('A', strtotime($batch->scheduled_start)) == 'PM' ? 'PM' : '') == 'PM' ? ' selected' : '' }}>PM</option>
                </select>
            </div>
            <input type="hidden" name="scheduled_start" id="scheduled_start">
        </div>
        <div>
            <label for="actual_start_date" class="block text-sm font-medium text-gray-700">Actual Start Date</label>
            <input type="date" name="actual_start_date" id="actual_start_date" value="{{ old('actual_start_date', $batch->actual_start ? date('Y-m-d', strtotime($batch->actual_start)) : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label for="actual_start_time_raw" class="block text-sm font-medium text-gray-700">Actual Start Time</label>
            <div class="flex space-x-2">
                <input type="time" name="actual_start_time_raw" id="actual_start_time_raw" value="{{ old('actual_start_time_raw', $batch->actual_start ? date('h:i', strtotime($batch->actual_start)) : '') }}" class="block w-full rounded-md border-gray-300 shadow-sm">
                <select name="actual_start_time_ampm" id="actual_start_time_ampm" class="block rounded-md border-gray-300 shadow-sm">
                    <option value="AM" {{ old('actual_start_time_ampm', $batch->actual_start && date('A', strtotime($batch->actual_start)) == 'AM' ? 'AM' : '') == 'AM' ? ' selected' : '' }}>AM</option>
                    <option value="PM" {{ old('actual_start_time_ampm', $batch->actual_start && date('A', strtotime($batch->actual_start)) == 'PM' ? 'PM' : '') == 'PM' ? ' selected' : '' }}>PM</option>
                </select>
            </div>
            <input type="hidden" name="actual_start" id="actual_start">
        </div>
        <div>
            <label for="actual_end_date" class="block text-sm font-medium text-gray-700">Actual End Date</label>
            <input type="date" name="actual_end_date" id="actual_end_date" value="{{ old('actual_end_date', $batch->actual_end ? date('Y-m-d', strtotime($batch->actual_end)) : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label for="actual_end_time_raw" class="block text-sm font-medium text-gray-700">Actual End Time</label>
            <div class="flex space-x-2">
                <input type="time" name="actual_end_time_raw" id="actual_end_time_raw" value="{{ old('actual_end_time_raw', $batch->actual_end ? date('h:i', strtotime($batch->actual_end)) : '') }}" class="block w-full rounded-md border-gray-300 shadow-sm">
                <select name="actual_end_time_ampm" id="actual_end_time_ampm" class="block rounded-md border-gray-300 shadow-sm">
                    <option value="AM" {{ old('actual_end_time_ampm', $batch->actual_end && date('A', strtotime($batch->actual_end)) == 'AM' ? 'AM' : '') == 'AM' ? ' selected' : '' }}>AM</option>
                    <option value="PM" {{ old('actual_end_time_ampm', $batch->actual_end && date('A', strtotime($batch->actual_end)) == 'PM' ? 'PM' : '') == 'PM' ? ' selected' : '' }}>PM</option>
                </select>
            </div>
            <input type="hidden" name="actual_end" id="actual_end">
        </div>
        <div class="mb-4">
            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity (Loaves Produced)</label>
            <input type="number" name="quantity" id="quantity" class="mt-1 block w-full border-gray-300 rounded-md" min="0" value="{{ old('quantity', $batch->quantity) }}">
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
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        function to24(time, ampm) {
            let [h, m] = time.split(':');
            h = parseInt(h);
            if (ampm === 'PM' && h < 12) h += 12;
            if (ampm === 'AM' && h === 12) h = 0;
            return (h < 10 ? '0' : '') + h + ':' + m;
        }
        // Scheduled Start
        const ssd = document.getElementById('scheduled_start_date').value;
        const sst = document.getElementById('scheduled_start_time_raw').value;
        const ssampm = document.getElementById('scheduled_start_time_ampm').value;
        document.getElementById('scheduled_start').value = ssd + 'T' + to24(sst, ssampm);
        // Actual Start
        const asd = document.getElementById('actual_start_date').value;
        const ast = document.getElementById('actual_start_time_raw').value;
        const asampm = document.getElementById('actual_start_time_ampm').value;
        if (asd && ast && asampm) {
            document.getElementById('actual_start').value = asd + 'T' + to24(ast, asampm);
        }
        // Actual End
        const aed = document.getElementById('actual_end_date').value;
        const aet = document.getElementById('actual_end_time_raw').value;
        const aeampm = document.getElementById('actual_end_time_ampm').value;
        if (aed && aet && aeampm) {
            document.getElementById('actual_end').value = aed + 'T' + to24(aet, aeampm);
        }
    });
</script>
@endsection