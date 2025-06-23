@extends('layouts.bakery-manager')

@section('header')
    Machine Details
@endsection

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('bakery.machines.index') }}" class="text-blue-600 hover:underline">&larr; Back to Machines</a>
        </div>
        <div class="bg-white shadow rounded p-6">
            <h2 class="text-lg font-semibold mb-4">{{ $machine->name }}</h2>
            <div class="mb-2"><strong>Type:</strong> {{ $machine->type }}</div>
            <div class="mb-2"><strong>Status:</strong> {{ ucfirst($machine->status) }}</div>
            <div class="mb-2"><strong>Last Maintenance:</strong> {{ $machine->last_maintenance_at ?? '-' }}</div>
            <div class="mb-2"><strong>Notes:</strong> {{ $machine->notes ?? '-' }}</div>
        </div>
        <div class="mt-4 flex space-x-2">
            <a href="{{ route('bakery.machines.edit', $machine) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
            <form action="{{ route('bakery.machines.destroy', $machine) }}" method="POST" onsubmit="return confirm('Delete this machine?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
            </form>
        </div>
    </div>
@endsection 