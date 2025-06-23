@extends('layouts.bakery-manager')

@section('header')
    Workforce Schedule
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Schedule Controls -->
                <div class="mb-6 flex justify-between items-center">
                    <div class="flex space-x-4">
                        <a href="{{ route('bakery.shifts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Shift</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="font-semibold">Week of {{ $startOfWeek->format('M d, Y') }}</span>
                    </div>
                </div>
                <!-- Schedule Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                @for($i = 0; $i < 7; $i++)
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $startOfWeek->copy()->addDays($i)->format('D') }}<br>
                                        <span class="text-xs text-gray-400">{{ $startOfWeek->copy()->addDays($i)->format('m/d') }}</span>
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($schedule as $row)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $row['user']->name }}</td>
                                    @foreach($row['days'] as $shift)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($shift)
                                                {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}<br>
                                                <span class="text-xs text-gray-400">{{ $shift->role }}</span><br>
                                                <a href="{{ route('bakery.shifts.edit', $shift) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                                            @else
                                                <a href="{{ route('bakery.shifts.create') }}?user_id={{ $row['user']->id }}&date={{ $startOfWeek->copy()->addDays($loop->index)->toDateString() }}" class="text-gray-400 hover:text-blue-600 text-xs">Add</a>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Staff Availability -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Staff Availability</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Available Today</h4>
                            <p class="text-2xl font-bold text-primary">12</p>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">On Leave</h4>
                            <p class="text-2xl font-bold text-yellow-600">2</p>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Total Staff</h4>
                            <p class="text-2xl font-bold text-gray-900">15</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 