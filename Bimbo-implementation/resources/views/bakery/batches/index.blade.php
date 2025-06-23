@extends('layouts.bakery-manager')

@section('header')
    Production Batches
@endsection

@section('content')
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-semibold">Production Batches</h2>
        <a href="{{ route('bakery.batches.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Batch</a>
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
@endsection 