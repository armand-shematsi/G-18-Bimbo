@extends('layouts.dashboard')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Supply Center</h2>
@endsection

@section('content')
<div class="bg-white shadow rounded-lg p-6 max-w-lg mx-auto">
    <form action="{{ route('admin.supply_centers.update', $center) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="name" value="{{ $center->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>
        <div class="mb-4">
            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
            <input type="text" name="location" id="location" value="{{ $center->location }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div class="mb-4">
            <label for="required_role" class="block text-sm font-medium text-gray-700">Required Role</label>
            <select name="required_role" id="required_role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">-- None --</option>
                <option value="driver" {{ $center->required_role == 'driver' ? 'selected' : '' }}>Driver</option>
                <option value="baker" {{ $center->required_role == 'baker' ? 'selected' : '' }}>Baker</option>
                <option value="loader" {{ $center->required_role == 'loader' ? 'selected' : '' }}>Loader</option>
                <option value="manager" {{ $center->required_role == 'manager' ? 'selected' : '' }}>Manager</option>
                <option value="staff" {{ $center->required_role == 'staff' ? 'selected' : '' }}>Staff</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="required_staff_count" class="block text-sm font-medium text-gray-700">Required Staffs Number</label>
            <input type="number" name="required_staff_count" id="required_staff_count" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" min="1" required value="{{ old('required_staff_count', $center->required_staff_count) }}">
            @error('required_staff_count')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="flex items-center justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update Center</button>
        </div>
    </form>
</div>
@endsection