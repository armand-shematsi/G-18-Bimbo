@extends('layouts.dashboard')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Supply Centers
</h2>
@endsection

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold">All Supply Centers</h3>
        <a href="{{ route('admin.supply_centers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Add Center</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Required Role</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($centers as $center)
                <tr>
                    <td class="px-4 py-2">{{ $center->name }}</td>
                    <td class="px-4 py-2">{{ $center->location }}</td>
                    <td class="px-4 py-2">{{ $center->required_role ?? '-' }}</td>
                    <td class="px-4 py-2">{{ ucfirst($center->type) }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.supply_centers.edit', $center) }}" class="text-blue-600 hover:underline">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection