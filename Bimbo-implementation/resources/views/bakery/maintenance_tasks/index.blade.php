@extends('layouts.bakery-manager')

@section('header')
    Maintenance Tasks
@endsection

@section('content')
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-semibold">Maintenance Tasks</h2>
        <a href="{{ route('bakery.maintenance-tasks.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Task</a>
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
                    <th class="px-4 py-2">Machine</th>
                    <th class="px-4 py-2">Scheduled For</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td class="border px-4 py-2">{{ $task->machine->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $task->scheduled_for }}</td>
                        <td class="border px-4 py-2">{{ ucfirst($task->status) }}</td>
                        <td class="border px-4 py-2">{{ Str::limit($task->description, 40) }}</td>
                        <td class="border px-4 py-2 flex space-x-2">
                            <a href="{{ route('bakery.maintenance-tasks.show', $task) }}" class="text-blue-600 hover:underline">View</a>
                            <a href="{{ route('bakery.maintenance-tasks.edit', $task) }}" class="text-yellow-600 hover:underline">Edit</a>
                            <form action="{{ route('bakery.maintenance-tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task?');">
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
        {{ $tasks->links() }}
    </div>
@endsection 