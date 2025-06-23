@extends('layouts.bakery-manager')

@section('header')
    Workforce Shifts
@endsection

@section('content')
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-semibold">Shifts</h2>
        <a href="{{ route('bakery.shifts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Assign Shift</a>
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
                    <th class="px-4 py-2">Staff</th>
                    <th class="px-4 py-2">Role</th>
                    <th class="px-4 py-2">Batch</th>
                    <th class="px-4 py-2">Start Time</th>
                    <th class="px-4 py-2">End Time</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shifts as $shift)
                    <tr>
                        <td class="border px-4 py-2">{{ $shift->user->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $shift->role }}</td>
                        <td class="border px-4 py-2">{{ $shift->productionBatch->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $shift->start_time }}</td>
                        <td class="border px-4 py-2">{{ $shift->end_time }}</td>
                        <td class="border px-4 py-2 flex space-x-2">
                            <a href="{{ route('bakery.shifts.show', $shift) }}" class="text-blue-600 hover:underline">View</a>
                            <a href="{{ route('bakery.shifts.edit', $shift) }}" class="text-yellow-600 hover:underline">Edit</a>
                            <form action="{{ route('bakery.shifts.destroy', $shift) }}" method="POST" onsubmit="return confirm('Delete this shift?');">
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
        {{ $shifts->links() }}
    </div>
@endsection 