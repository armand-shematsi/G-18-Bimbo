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
            <select name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">Select batch name</option>
                <option value="White Bread" {{ old('name', $batch->name) == 'White Bread' ? 'selected' : '' }}>White Bread</option>
                <option value="Brown Bread" {{ old('name', $batch->name) == 'Brown Bread' ? 'selected' : '' }}>Brown Bread</option>
                <option value="Baguette" {{ old('name', $batch->name) == 'Baguette' ? 'selected' : '' }}>Baguette</option>
                <option value="Ciabatta" {{ old('name', $batch->name) == 'Ciabatta' ? 'selected' : '' }}>Ciabatta</option>
                <option value="Rye Bread" {{ old('name', $batch->name) == 'Rye Bread' ? 'selected' : '' }}>Rye Bread</option>
                <option value="Multigrain" {{ old('name', $batch->name) == 'Multigrain' ? 'selected' : '' }}>Multigrain</option>
                <option value="Sourdough" {{ old('name', $batch->name) == 'Sourdough' ? 'selected' : '' }}>Sourdough</option>
                <option value="Brioche" {{ old('name', $batch->name) == 'Brioche' ? 'selected' : '' }}>Brioche</option>
            </select>
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
        <div class="mb-4">
            <label for="production_line_id" class="block text-sm font-medium text-gray-700">Production Line</label>
            <select name="production_line_id" id="production_line_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">Select a line</option>
                <option value="A" {{ old('production_line_id', $batch->production_line_id) == 'A' ? 'selected' : '' }}>Line A</option>
                <option value="B" {{ old('production_line_id', $batch->production_line_id) == 'B' ? 'selected' : '' }}>Line B</option>
                <option value="C" {{ old('production_line_id', $batch->production_line_id) == 'C' ? 'selected' : '' }}>Line C</option>
                <option value="D" {{ old('production_line_id', $batch->production_line_id) == 'D' ? 'selected' : '' }}>Line D</option>
                <option value="E" {{ old('production_line_id', $batch->production_line_id) == 'E' ? 'selected' : '' }}>Line E</option>
                <option value="F" {{ old('production_line_id', $batch->production_line_id) == 'F' ? 'selected' : '' }}>Line F</option>
                <option value="G" {{ old('production_line_id', $batch->production_line_id) == 'G' ? 'selected' : '' }}>Line G</option>
                <option value="H" {{ old('production_line_id', $batch->production_line_id) == 'H' ? 'selected' : '' }}>Line H</option>
            </select>
            @error('production_line_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="staff" class="block text-sm font-medium text-gray-700">Assign Staff</label>
            <select name="staff[]" id="staff" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @foreach($staff as $user)
                <option value="{{ $user->id }}" {{ (collect(old('staff', $batch->shifts->pluck('user_id')->toArray()))->contains($user->id)) ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
            <small class="text-gray-500">Hold Ctrl (Windows) or Cmd (Mac) to select multiple staff.</small>
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

    // --- AM/PM Dropdown Sync for all time pickers ---
    function updateTimeInput(timeInput, ampmSelect) {
        ampmSelect.addEventListener('change', function() {
            let [h, m] = timeInput.value.split(':');
            if (!h || !m) return;
            h = parseInt(h);
            if (this.value === 'PM' && h < 12) h += 12;
            if (this.value === 'AM' && h === 12) h = 0;
            h = (h < 10 ? '0' : '') + h;
            timeInput.value = h + ':' + m;
        });
    }
    updateTimeInput(document.getElementById('scheduled_start_time_raw'), document.getElementById('scheduled_start_time_ampm'));
    updateTimeInput(document.getElementById('actual_start_time_raw'), document.getElementById('actual_start_time_ampm'));
    updateTimeInput(document.getElementById('actual_end_time_raw'), document.getElementById('actual_end_time_ampm'));
</script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery (required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#staff').select2({
            placeholder: "Select staff",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endsection