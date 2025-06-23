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
                @if($batch->shifts->count())
                    <ul class="list-disc pl-6">
                        @foreach($batch->shifts as $shift)
                            <li>{{ $shift->user->name ?? '-' }} ({{ $shift->role }}, {{ $shift->start_time }} - {{ $shift->end_time }})</li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-gray-500">No shifts assigned.</div>
                @endif
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
@endsection 