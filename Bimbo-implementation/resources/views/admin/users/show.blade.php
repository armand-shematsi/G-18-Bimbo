@extends('layouts.dashboard')

@section('navigation-links')
    <!-- Navigation links for admin dashboard -->
    <a href="{{ route('admin.vendors.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Manage Vendors
    </a>
    <a href="{{ route('admin.analytics') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Analytics
    </a>
    <a href="{{ route('admin.settings') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        System Settings
    </a>
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Manage Users
    </a>
@endsection

@section('header')
    <div class="flex justify-between items-center">
        <!-- Page header -->
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Details') }}
        </h2>
    </div>
@endsection

@section('content')
    <!-- User details card -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="mb-4">
                <!-- User name -->
                <h3 class="text-lg font-medium text-gray-900">Name</h3>
                <p class="mt-1 text-sm text-gray-600">{{ $user->name }}</p>
            </div>
            <div class="mb-4">
                <!-- User email -->
                <h3 class="text-lg font-medium text-gray-900">Email</h3>
                <p class="mt-1 text-sm text-gray-600">{{ $user->email }}</p>
            </div>
            <div class="mb-4">
                <!-- User role -->
                <h3 class="text-lg font-medium text-gray-900">Role</h3>
                <p class="mt-1 text-sm text-gray-600">{{ ucfirst($user->role) }}</p>
            </div>
            <div class="flex items-center justify-end">
                <!-- Edit user button -->
                <a href="{{ route('admin.users.edit', $user) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit User
                </a>
            </div>
        </div>
    </div>
@endsection