@extends('layouts.bakery-manager')

@section('header')
Production Batches
@endsection

@section('content')
<div class="flex justify-between mb-4">
    <h2 class="text-xl font-semibold">Production Batches</h2>
    <a href="{{ route('bakery.batches.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ New Batch</a>
</div>
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif
<div class="overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">ID</th>
                <th class="px-4 py-2 text-left">Name</th>
                <th class="px-4 py-2 text-left">Scheduled Start</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($batches as $batch)
            <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                <td class="border px-4 py-2">{{ $batch->id }}</td>
                <td class="border px-4 py-2">{{ $batch->name }}</td>
                <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($batch->scheduled_start)->format('M d, Y H:i') }}</td>
                <td class="border px-4 py-2">
                    @php
                    $statusColors = [
                    'planned' => 'bg-blue-100 text-blue-800',
                    'active' => 'bg-green-100 text-green-800',
                    'completed' => 'bg-gray-200 text-gray-800',
                    'cancelled' => 'bg-red-100 text-red-800',
                    ];
                    @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$batch->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($batch->status) }}
                    </span>
                </td>
                <td class="border px-4 py-2 space-x-2">
                    <a href="{{ route('bakery.batches.show', $batch) }}" class="text-blue-600 hover:underline">View</a>
                    <a href="{{ route('bakery.batches.edit', $batch) }}" class="text-yellow-600 hover:underline">Edit</a>
                    <form action="{{ route('bakery.batches.destroy', $batch) }}" method="POST" class="inline" onsubmit="return confirm('Delete this batch?');">
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
@endsection