@extends('layouts.bakery-manager')

@section('header')
    Maintenance Task Details
@endsection

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('bakery.maintenance-tasks.index') }}" class="text-blue-600 hover:underline">&larr; Back to Maintenance Tasks</a>
        </div>
        <div class="bg-white shadow rounded p-6">
            <h2 class="text-lg font-semibold mb-4">{{ $maintenanceTask->machine->name ?? '-' }}</h2>
            <div class="mb-2"><strong>Scheduled For:</strong> {{ $maintenanceTask->scheduled_for }}</div>
            <div class="mb-2"><strong>Status:</strong> {{ ucfirst($maintenanceTask->status) }}</div>
            <div class="mb-2"><strong>Description:</strong> {{ $maintenanceTask->description }}</div>
            <div class="mb-2"><strong>Completed At:</strong> {{ $maintenanceTask->completed_at ?? '-' }}</div>
        </div>
        <div class="mt-4 flex space-x-2">
            <a href="{{ route('bakery.maintenance-tasks.edit', $maintenanceTask) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
            <form action="{{ route('bakery.maintenance-tasks.destroy', $maintenanceTask) }}" method="POST" onsubmit="return confirm('Delete this task?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
            </form>
        </div>
    </div>
@endsection 