@extends('layouts.dashboard')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Supply Center</h2>
@endsection

@section('content')
<div class="bg-white shadow rounded-lg p-6 max-w-lg mx-auto">
    <form action="{{ route('admin.supply_centers.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>
        <div class="mb-4">
            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
            <input type="text" name="location" id="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div class="mb-4">
            <label for="required_role" class="block text-sm font-medium text-gray-700">Required Role</label>
            <select name="required_role" id="required_role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">-- None --</option>
                <option value="driver">Driver</option>
                <option value="baker">Baker</option>
                <option value="loader">Loader</option>
                <option value="manager">Manager</option>
                <option value="staff">Staff</option>
            </select>
        </div>
        <div class="flex items-center justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Add Center</button>
        </div>
    </form>
</div>
@endsection