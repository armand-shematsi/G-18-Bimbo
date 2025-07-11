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