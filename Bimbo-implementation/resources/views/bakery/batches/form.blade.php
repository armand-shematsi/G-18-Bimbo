@php
$isEdit = isset($batch);
@endphp
<form id="batch-form" method="POST" action="{{ $isEdit ? route('bakery.batches.update', $batch) : route('bakery.batches.store') }}">
    @csrf
    @if($isEdit)
    @method('PUT')
    @endif
    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="mb-4">
        <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
        <select name="product_id" id="product_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Select product</option>
            @foreach($products as $product)
            <option value="{{ $product->id }}" {{ old('product_id', $isEdit ? ($batch->product_id ?? null) : null) == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
            @endforeach
        </select>
        @error('product_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <select name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Select batch name</option>
            @if(isset($breadProducts) && count($breadProducts))
            @foreach($breadProducts as $productName)
            <option value="{{ $productName }}" {{ old('name', $isEdit ? $batch->name : null) == $productName ? 'selected' : '' }}>{{ $productName }}</option>
            @endforeach
            @else
            <option value="White Bread" {{ old('name', $isEdit ? $batch->name : null) == 'White Bread' ? 'selected' : '' }}>White Bread</option>
            <option value="Brown Bread" {{ old('name', $isEdit ? $batch->name : null) == 'Brown Bread' ? 'selected' : '' }}>Brown Bread</option>
            <option value="Baguette" {{ old('name', $isEdit ? $batch->name : null) == 'Baguette' ? 'selected' : '' }}>Baguette</option>
            <option value="Ciabatta" {{ old('name', $isEdit ? $batch->name : null) == 'Ciabatta' ? 'selected' : '' }}>Ciabatta</option>
            <option value="Rye Bread" {{ old('name', $isEdit ? $batch->name : null) == 'Rye Bread' ? 'selected' : '' }}>Rye Bread</option>
            <option value="Multigrain" {{ old('name', $isEdit ? $batch->name : null) == 'Multigrain' ? 'selected' : '' }}>Multigrain</option>
            <option value="Sourdough" {{ old('name', $isEdit ? $batch->name : null) == 'Sourdough' ? 'selected' : '' }}>Sourdough</option>
            <option value="Brioche" {{ old('name', $isEdit ? $batch->name : null) == 'Brioche' ? 'selected' : '' }}>Brioche</option>
            @endif
        </select>
        @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div class="mb-4">
        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="planned" {{ old('status', $isEdit ? $batch->status : null) == 'planned' ? ' selected' : '' }}>Planned</option>
            <option value="active" {{ old('status', $isEdit ? $batch->status : null) == 'active' ? ' selected' : '' }}>Active</option>
            <option value="completed" {{ old('status', $isEdit ? $batch->status : null) == 'completed' ? ' selected' : '' }}>Completed</option>
            <option value="cancelled" {{ old('status', $isEdit ? $batch->status : null) == 'cancelled' ? ' selected' : '' }}>Cancelled</option>
        </select>
        @error('status')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
        <label for="scheduled_start_date" class="block text-sm font-medium text-gray-700">Scheduled Start Date</label>
        <input type="date" name="scheduled_start_date" id="scheduled_start_date" value="{{ old('scheduled_start_date', $isEdit && $batch->scheduled_start ? date('Y-m-d', strtotime($batch->scheduled_start)) : '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="scheduled_start_time_raw" class="block text-sm font-medium text-gray-700">Scheduled Start Time</label>
        <div class="flex space-x-2">
            <input type="time" name="scheduled_start_time_raw" id="scheduled_start_time_raw" value="{{ old('scheduled_start_time_raw', $isEdit && $batch->scheduled_start ? date('h:i', strtotime($batch->scheduled_start)) : '') }}" required class="block w-full rounded-md border-gray-300 shadow-sm">
            <select name="scheduled_start_time_ampm" id="scheduled_start_time_ampm" required class="block rounded-md border-gray-300 shadow-sm">
                <option value="AM" {{ old('scheduled_start_time_ampm', $isEdit && $batch->scheduled_start && date('A', strtotime($batch->scheduled_start)) == 'AM' ? 'AM' : '') == 'AM' ? ' selected' : '' }}>AM</option>
                <option value="PM" {{ old('scheduled_start_time_ampm', $isEdit && $batch->scheduled_start && date('A', strtotime($batch->scheduled_start)) == 'PM' ? 'PM' : '') == 'PM' ? ' selected' : '' }}>PM</option>
            </select>
        </div>
        <input type="hidden" name="scheduled_start" id="scheduled_start">
    </div>
    <div>
        <label for="actual_start_date" class="block text-sm font-medium text-gray-700">Actual Start Date</label>
        <input type="date" name="actual_start_date" id="actual_start_date" value="{{ old('actual_start_date', $isEdit && $batch->actual_start ? date('Y-m-d', strtotime($batch->actual_start)) : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="actual_start_time_raw" class="block text-sm font-medium text-gray-700">Actual Start Time</label>
        <div class="flex space-x-2">
            <input type="time" name="actual_start_time_raw" id="actual_start_time_raw" value="{{ old('actual_start_time_raw', $isEdit && $batch->actual_start ? date('h:i', strtotime($batch->actual_start)) : '') }}" class="block w-full rounded-md border-gray-300 shadow-sm">
            <select name="actual_start_time_ampm" id="actual_start_time_ampm" class="block rounded-md border-gray-300 shadow-sm">
                <option value="AM" {{ old('actual_start_time_ampm', $isEdit && $batch->actual_start && date('A', strtotime($batch->actual_start)) == 'AM' ? 'AM' : '') == 'AM' ? ' selected' : '' }}>AM</option>
                <option value="PM" {{ old('actual_start_time_ampm', $isEdit && $batch->actual_start && date('A', strtotime($batch->actual_start)) == 'PM' ? 'PM' : '') == 'PM' ? ' selected' : '' }}>PM</option>
            </select>
        </div>
        <input type="hidden" name="actual_start" id="actual_start">
    </div>
    <div>
        <label for="actual_end_date" class="block text-sm font-medium text-gray-700">Actual End Date</label>
        <input type="date" name="actual_end_date" id="actual_end_date" value="{{ old('actual_end_date', $isEdit && $batch->actual_end ? date('Y-m-d', strtotime($batch->actual_end)) : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="actual_end_time_raw" class="block text-sm font-medium text-gray-700">Actual End Time</label>
        <div class="flex space-x-2">
            <input type="time" name="actual_end_time_raw" id="actual_end_time_raw" value="{{ old('actual_end_time_raw', $isEdit && $batch->actual_end ? date('h:i', strtotime($batch->actual_end)) : '') }}" class="block w-full rounded-md border-gray-300 shadow-sm">
            <select name="actual_end_time_ampm" id="actual_end_time_ampm" class="block rounded-md border-gray-300 shadow-sm">
                <option value="AM" {{ old('actual_end_time_ampm', $isEdit && $batch->actual_end && date('A', strtotime($batch->actual_end)) == 'AM' ? 'AM' : '') == 'AM' ? ' selected' : '' }}>AM</option>
                <option value="PM" {{ old('actual_end_time_ampm', $isEdit && $batch->actual_end && date('A', strtotime($batch->actual_end)) == 'PM' ? 'PM' : '') == 'PM' ? ' selected' : '' }}>PM</option>
            </select>
        </div>
        <input type="hidden" name="actual_end" id="actual_end">
    </div>
    <div class="mb-4">
        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity (Loaves Produced)</label>
        <input type="number" name="quantity" id="quantity" class="mt-1 block w-full border-gray-300 rounded-md" min="0" value="{{ old('quantity', $isEdit ? $batch->quantity : null) }}">
    </div>
    <div class="mb-4">
        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
        <textarea name="notes" id="notes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('notes', $isEdit ? $batch->notes : null) }}</textarea>
        @error('notes')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div class="mb-4">
        <label for="production_line_id" class="block text-sm font-medium text-gray-700">Production Line</label>
        <select name="production_line_id" id="production_line_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Select a line</option>
            @foreach($productionLines as $line)
            <option value="{{ $line->id }}" data-line-name="{{ strtolower($line->name) }}" {{ old('production_line_id', $isEdit ? $batch->production_line_id : null) == $line->id ? 'selected' : '' }}>{{ $line->name }}</option>
            @endforeach
        </select>
        @error('production_line_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ $isEdit ? 'Update Batch' : 'Create Batch' }}</button>
    <a href="{{ route('bakery.batches.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
</form>
<script>
    function to24(time, ampm) {
        let [h, m] = time.split(':');
        h = parseInt(h);
        if (ampm === 'PM' && h < 12) h += 12;
        if (ampm === 'AM' && h === 12) h = 0;
        return (h < 10 ? '0' : '') + h + ':' + m;
    }

    function to12(time) {
        let [h, m] = time.split(':');
        h = parseInt(h);
        let ampm = h >= 12 ? 'PM' : 'AM';
        h = h % 12;
        if (h === 0) h = 12;
        return [(h < 10 ? '0' : '') + h + ':' + m, ampm];
    }

    function syncTimeAndAmPm(timeInputId, ampmSelectId) {
        const timeInput = document.getElementById(timeInputId);
        const ampmSelect = document.getElementById(ampmSelectId);
        if (!timeInput || !ampmSelect) return;
        // When AM/PM changes, update time input
        ampmSelect.addEventListener('change', function() {
            let [h, m] = timeInput.value.split(':');
            if (!h || !m) return;
            h = parseInt(h);
            if (this.value === 'PM' && h < 12) h += 12;
            if (this.value === 'AM' && h === 12) h = 0;
            h = (h < 10 ? '0' : '') + h;
            timeInput.value = h + ':' + m;
        });
        // When time input changes, update AM/PM
        timeInput.addEventListener('change', function() {
            if (!this.value) return;
            let [h, m] = this.value.split(':');
            h = parseInt(h);
            if (h >= 12) {
                ampmSelect.value = 'PM';
            } else {
                ampmSelect.value = 'AM';
            }
            // Convert to 12-hour format for display if needed
            let displayH = h % 12;
            if (displayH === 0) displayH = 12;
            this.value = (displayH < 10 ? '0' : '') + displayH + ':' + m;
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        // Synchronize time and AM/PM fields for all time inputs
        function syncTimeAndAmPm(timeInputId, ampmSelectId) {
            const timeInput = document.getElementById(timeInputId);
            const ampmSelect = document.getElementById(ampmSelectId);
            if (!timeInput || !ampmSelect) return;
            // On time input change, update AM/PM select
            timeInput.addEventListener('change', function() {
                let [h, m] = timeInput.value.split(':');
                h = parseInt(h);
                if (isNaN(h)) return;
                if (h >= 12) {
                    ampmSelect.value = 'PM';
                } else {
                    ampmSelect.value = 'AM';
                }
            });
            // On AM/PM select change, update time input if needed
            ampmSelect.addEventListener('change', function() {
                let [h, m] = timeInput.value.split(':');
                h = parseInt(h);
                if (isNaN(h)) return;
                if (ampmSelect.value === 'PM' && h < 12) {
                    h += 12;
                }
                if (ampmSelect.value === 'AM' && h >= 12) {
                    h -= 12;
                }
                if (h < 0) h = 0;
                timeInput.value = (h < 10 ? '0' : '') + h + ':' + (m || '00');
            });
            // On page load, set AM/PM based on initial time value
            if (timeInput.value) {
                let [h, m] = timeInput.value.split(':');
                h = parseInt(h);
                if (!isNaN(h)) {
                    if (h >= 12) {
                        ampmSelect.value = 'PM';
                    } else {
                        ampmSelect.value = 'AM';
                    }
                }
            }
        }
        syncTimeAndAmPm('scheduled_start_time_raw', 'scheduled_start_time_ampm');
        syncTimeAndAmPm('actual_start_time_raw', 'actual_start_time_ampm');
        syncTimeAndAmPm('actual_end_time_raw', 'actual_end_time_ampm');
        document.getElementById('batch-form').addEventListener('submit', function(e) {
            // Helper to convert local date/time to UTC ISO string
            function toUTCISO(date, time, ampm) {
                if (!date || !time || !ampm) return '';
                let [h, m] = time.split(':');
                h = parseInt(h);
                if (ampm === 'PM' && h < 12) h += 12;
                if (ampm === 'AM' && h === 12) h = 0;
                // Compose local datetime string
                const local = new Date(date + 'T' + (h < 10 ? '0' : '') + h + ':' + m + ':00');
                // Convert to UTC ISO string
                return local.toISOString();
            }
            // Scheduled Start
            const ssd = document.getElementById('scheduled_start_date').value;
            const sst = document.getElementById('scheduled_start_time_raw').value;
            const ssampm = document.getElementById('scheduled_start_time_ampm').value;
            const scheduledStartField = document.getElementById('scheduled_start');
            scheduledStartField.value = toUTCISO(ssd, sst, ssampm);
            // Actual Start
            const asd = document.getElementById('actual_start_date').value;
            const ast = document.getElementById('actual_start_time_raw').value;
            const asampm = document.getElementById('actual_start_time_ampm').value;
            const actualStartField = document.getElementById('actual_start');
            actualStartField.value = toUTCISO(asd, ast, asampm);
            // Actual End
            const aed = document.getElementById('actual_end_date').value;
            const aet = document.getElementById('actual_end_time_raw').value;
            const aeampm = document.getElementById('actual_end_time_ampm').value;
            const actualEndField = document.getElementById('actual_end');
            actualEndField.value = toUTCISO(aed, aet, aeampm);
            // Prevent submit if scheduled_start is not set
            if (!scheduledStartField.value) {
                e.preventDefault();
                alert('Please fill in all scheduled start date and time fields.');
                return false;
            }
        });
    });
</script>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const batchNameSelect = document.getElementById('name');
        const lineSelect = document.getElementById('production_line_id');
        const allOptions = Array.from(lineSelect.options).filter(opt => opt.value !== '');

        function filterLines() {
            const batchName = batchNameSelect.value.trim().toLowerCase();
            let found = false;
            lineSelect.innerHTML = '<option value="">Select a line</option>';
            allOptions.forEach(opt => {
                if (batchName && opt.dataset.lineName && opt.dataset.lineName.includes(batchName)) {
                    lineSelect.appendChild(opt.cloneNode(true));
                    found = true;
                }
            });
            if (!found) {
                allOptions.forEach(opt => lineSelect.appendChild(opt.cloneNode(true)));
            }
        }
        batchNameSelect.addEventListener('change', filterLines);
        // Initial filter on page load (for edit form)
        filterLines();
    });
</script>
@endpush