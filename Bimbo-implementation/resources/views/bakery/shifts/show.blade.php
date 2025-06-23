@extends('layouts.bakery-manager')

@section('header')
    Shift Details
@endsection

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('bakery.shifts.index') }}" class="text-blue-600 hover:underline">&larr; Back to Shifts</a>
        </div>
        <div class="bg-white shadow rounded p-6">
            <h2 class="text-lg font-semibold mb-4">{{ $shift->user->name ?? '-' }}</h2>
            <div class="mb-2"><strong>Role:</strong> {{ $shift->role }}</div>
            <div class="mb-2"><strong>Batch:</strong> {{ $shift->productionBatch->name ?? '-' }}</div>
            <div class="mb-2"><strong>Start Time:</strong> {{ $shift->start_time }}</div>
            <div class="mb-2"><strong>End Time:</strong> {{ $shift->end_time }}</div>
        </div>
        <div class="mt-4 flex space-x-2">
            <a href="{{ route('bakery.shifts.edit', $shift) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
            <form action="{{ route('bakery.shifts.destroy', $shift) }}" method="POST" onsubmit="return confirm('Delete this shift?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
            </form>
        </div>
    </div>
@endsection 