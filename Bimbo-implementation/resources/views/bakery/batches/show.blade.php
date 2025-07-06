@extends('layouts.bakery-manager')

@section('header')
Batch Details
@endsection

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('bakery.batches.index') }}" class="text-blue-600 hover:underline">&larr; Back to Batches</a>
    </div>
    <div class="bg-white shadow rounded p-6">
        <h2 class="text-lg font-semibold mb-4">{{ $batch->name }}</h2>
        <div class="mb-2"><strong>Status:</strong> {{ ucfirst($batch->status) }}</div>
        <div class="mb-2"><strong>Scheduled Start:</strong> {{ $batch->scheduled_start }}</div>
        <div class="mb-2"><strong>Actual Start:</strong> {{ $batch->actual_start ?? '-' }}</div>
        <div class="mb-2"><strong>Actual End:</strong> {{ $batch->actual_end ?? '-' }}</div>
        <div class="mb-2"><strong>Notes:</strong> {{ $batch->notes ?? '-' }}</div>
        <div class="mt-6">
            <h3 class="font-semibold mb-2">Assigned Shifts</h3>
            <div class="assigned-shifts-list">
                @if($batch->shifts->count())
                <ul class="list-disc pl-6">
                    @foreach($batch->shifts as $shift)
                    <li>
                        <a href="{{ route('bakery.shifts.show', $shift) }}" class="text-blue-600 hover:underline">
                            {{ $shift->user->name ?? 'Unassigned' }} ({{ $shift->role }}, {{ $shift->start_time }} - {{ $shift->end_time }})
                        </a>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="text-gray-500">No shifts assigned.</div>
                @endif
            </div>
            <div class="mt-4">
                <button id="openAssignShiftModal" class="px-4 py-2 bg-blue-600 text-white rounded">Assign Shift</button>
            </div>
        </div>
        <div class="mt-6">
            <h3 class="font-semibold mb-2">Ingredients Used</h3>
            @if($batch->ingredients->count())
            <ul class="list-disc pl-6">
                @foreach($batch->ingredients as $ingredient)
                <li>{{ $ingredient->name }}: {{ $ingredient->pivot->quantity_used }} {{ $ingredient->unit }}</li>
                @endforeach
            </ul>
            @else
            <div class="text-gray-500">No ingredients recorded for this batch.</div>
            @endif
        </div>
    </div>
    <div class="mt-4 flex space-x-2">
        <a href="{{ route('bakery.batches.edit', $batch) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
        <form action="{{ route('bakery.batches.destroy', $batch) }}" method="POST" onsubmit="return confirm('Delete this batch?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
        </form>
    </div>
</div>

<!-- Assign Shift Modal -->
<div id="assignShiftModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:2rem; border-radius:8px; max-width:400px; margin:auto; position:relative;">
        <button onclick="document.getElementById('assignShiftModal').style.display='none'" style="position:absolute; top:8px; right:12px;">&times;</button>
        <h2 class="text-lg font-bold mb-4">Assign Shift</h2>
        <form id="assignShiftForm">
            <label>Staff:</label>
            <select name="user_id" class="w-full mb-4 border rounded p-2" required>
                @foreach(\App\Models\User::where('role', 'staff')->get() as $staff)
                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                @endforeach
            </select>
            <div>
                <label for="start_time_date" class="block text-sm font-medium text-gray-700">Start Time Date</label>
                <input type="date" name="start_time_date" id="start_time_date" class="w-full mb-2 border rounded p-2">
            </div>
            <div>
                <label for="start_time_raw" class="block text-sm font-medium text-gray-700">Start Time</label>
                <div class="flex space-x-2 mb-2">
                    <input type="time" name="start_time_raw" id="start_time_raw" class="w-full border rounded p-2">
                    <select name="start_time_ampm" id="start_time_ampm" class="border rounded p-2">
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                    </select>
                </div>
                <input type="hidden" name="start_time" id="start_time">
            </div>
            <div>
                <label for="end_time_date" class="block text-sm font-medium text-gray-700">End Time Date</label>
                <input type="date" name="end_time_date" id="end_time_date" class="w-full mb-2 border rounded p-2">
            </div>
            <div>
                <label for="end_time_raw" class="block text-sm font-medium text-gray-700">End Time</label>
                <div class="flex space-x-2 mb-2">
                    <input type="time" name="end_time_raw" id="end_time_raw" class="w-full border rounded p-2">
                    <select name="end_time_ampm" id="end_time_ampm" class="border rounded p-2">
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                    </select>
                </div>
                <input type="hidden" name="end_time" id="end_time">
            </div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Assign</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('openAssignShiftModal').onclick = function() {
        document.getElementById('assignShiftModal').style.display = 'flex';
    };
    document.getElementById('assignShiftForm').onsubmit = function(e) {
        e.preventDefault();
        const form = e.target;
        var batchId = "{{ $batch->id }}";
        function to24(time, ampm) {
            let [h, m] = time.split(':');
            h = parseInt(h);
            if (ampm === 'PM' && h < 12) h += 12;
            if (ampm === 'AM' && h === 12) h = 0;
            return (h < 10 ? '0' : '') + h + ':' + m;
        }
        const sd = document.getElementById('start_time_date').value;
        const st = document.getElementById('start_time_raw').value;
        const stam = document.getElementById('start_time_ampm').value;
        const startTime = sd + 'T' + to24(st, stam);
        const ed = document.getElementById('end_time_date').value;
        const et = document.getElementById('end_time_raw').value;
        const etam = document.getElementById('end_time_ampm').value;
        const endTime = ed + 'T' + to24(et, etam);
        fetch(`/batches/${batchId}/assign-shift`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_id: form.user_id.value,
                start_time: startTime,
                end_time: endTime
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Shift assigned!');
                document.getElementById('assignShiftModal').style.display = 'none';
                location.reload();
            } else {
                alert('Error assigning shift');
            }
        });
    };
</script>
@endpush
@endsection