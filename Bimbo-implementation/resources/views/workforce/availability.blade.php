@extends('layouts.bakery-manager')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Staff Availability
</h2>
@endsection

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded shadow p-6 mt-8">
    <h3 class="text-lg font-bold mb-4">Staff Availability for {{ $date }}</h3>
    <form method="GET" class="flex flex-wrap gap-4 mb-4 items-end">
        <div>
            <label class="block text-sm font-medium">Date</label>
            <input type="date" name="date" value="{{ $date }}" class="border rounded px-2 py-1" onchange="this.form.submit()">
        </div>
        <div>
            <label class="block text-sm font-medium">Supply Center</label>
            <select name="supply_center_id" class="border rounded px-2 py-1" onchange="this.form.submit()">
                <option value="">All Centers</option>
                @foreach($supplyCenters as $center)
                <option value="{{ $center->id }}" {{ $supplyCenterId == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>
                @endforeach
            </select>
        </div>
    </form>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Supply Center</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($availability as $staff)
                <tr>
                    <td class="px-4 py-2">{{ $staff['name'] }}</td>
                    <td class="px-4 py-2">{{ $staff['supply_center'] }}</td>
                    <td class="px-4 py-2">
                        @if($staff['status'] === 'Available')
                        <span class="inline-block px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-semibold">Available</span>
                        @elseif($staff['status'] === 'On Shift')
                        <span class="inline-block px-2 py-1 rounded bg-blue-100 text-blue-800 text-xs font-semibold">On Shift</span>
                        @elseif($staff['status'] === 'Absent')
                        <span class="inline-block px-2 py-1 rounded bg-red-100 text-red-800 text-xs font-semibold">Absent</span>
                        @else
                        <span class="inline-block px-2 py-1 rounded bg-gray-100 text-gray-800 text-xs font-semibold">Unknown</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection