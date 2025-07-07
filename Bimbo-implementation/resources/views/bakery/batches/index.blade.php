@extends('layouts.bakery-manager')

@section('header')
Production Batches
@endsection

@section('content')
<div class="flex justify-between mb-4">
    <h2 class="text-xl font-semibold">Production Batches</h2>
    <button id="openStartBatchModal" class="bg-blue-600 text-white px-4 py-2 rounded">+ Start Batch</button>
</div>
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif
<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Scheduled Start</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($batches as $batch)
            <tr>
                <td class="border px-4 py-2">{{ $batch->name }}</td>
                <td class="border px-4 py-2">{{ ucfirst($batch->status) }}</td>
                <td class="border px-4 py-2">{{ $batch->scheduled_start }}</td>
                <td class="border px-4 py-2 flex space-x-2">
                    <a href="{{ route('bakery.batches.show', $batch) }}" class="text-blue-600 hover:underline">View</a>
                    <a href="{{ route('bakery.batches.edit', $batch) }}" class="text-yellow-600 hover:underline">Edit</a>
                    <form action="{{ route('bakery.batches.destroy', $batch) }}" method="POST" onsubmit="return confirm('Delete this batch?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">
    {{ $batches->links() }}
</div>

<!-- Start Batch Modal -->
<div id="startBatchModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:2rem; border-radius:8px; max-width:400px; margin:auto; position:relative;">
        <button onclick="document.getElementById('startBatchModal').style.display='none'" style="position:absolute; top:8px; right:12px;">&times;</button>
        <h2 class="text-lg font-bold mb-4">Start New Batch</h2>
        <form id="startBatchForm">
            <label>Batch Name:</label>
            <select name="name" class="w-full mb-4 border rounded p-2" required>
                @foreach(\App\Models\Product::all() as $product)
                <option value="{{ $product->name }}">{{ $product->name }}</option>
                @endforeach
            </select>
            <label>Production Line:</label>
            <select name="production_line_id" class="w-full mb-4 border rounded p-2" required>
                @foreach(\App\Models\ProductionLine::all() as $line)
                <option value="{{ $line->id }}">{{ $line->name }}</option>
                @endforeach
            </select>
            <div>
                <label for="scheduled_start_date" class="block text-sm font-medium text-gray-700">Scheduled Start Date</label>
                <input type="date" name="scheduled_start_date" id="scheduled_start_date" class="w-full mb-2 border rounded p-2" required>
            </div>
            <div>
                <label for="scheduled_start_time_raw" class="block text-sm font-medium text-gray-700">Scheduled Start Time</label>
                <div class="flex space-x-2 mb-2">
                    <input type="time" name="scheduled_start_time_raw" id="scheduled_start_time_raw" class="w-full border rounded p-2" required>
                    <select name="scheduled_start_time_ampm" id="scheduled_start_time_ampm" class="border rounded p-2" required>
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                    </select>
                </div>
                <input type="hidden" name="scheduled_start" id="scheduled_start">
            </div>
            <label>Notes:</label>
            <textarea name="notes" class="w-full mb-4 border rounded p-2"></textarea>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Start Batch</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('openStartBatchModal').onclick = function() {
        document.getElementById('startBatchModal').style.display = 'flex';
    };
    document.getElementById('startBatchForm').onsubmit = function(e) {
        e.preventDefault();
        const form = e.target;
        fetch('/batches', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    name: form.name.value,
                    production_line_id: form.production_line_id.value,
                    scheduled_start: form.scheduled_start.value,
                    notes: form.notes.value,
                    status: 'Active'
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Batch started!');
                    document.getElementById('startBatchModal').style.display = 'none';
                    location.reload();
                } else {
                    alert('Error starting batch');
                }
            });
    };
    document.querySelector('form').addEventListener('submit', function(e) {
        function to24(time, ampm) {
            let [h, m] = time.split(':');
            h = parseInt(h);
            if (ampm === 'PM' && h < 12) h += 12;
            if (ampm === 'AM' && h === 12) h = 0;
            return (h < 10 ? '0' : '') + h + ':' + m;
        }
        const sd = document.getElementById('scheduled_start_date').value;
        const st = document.getElementById('scheduled_start_time_raw').value;
        const stam = document.getElementById('scheduled_start_time_ampm').value;
        document.getElementById('scheduled_start').value = sd + 'T' + to24(st, stam);
    });
</script>
@endpush