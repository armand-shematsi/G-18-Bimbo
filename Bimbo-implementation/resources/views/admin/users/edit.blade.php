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
            {{ __('Edit User') }}
        </h2>
    </div>
@endsection

@section('content')
    <!-- User edit form -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <!-- Name input -->
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" value="{{ $user->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div class="mb-4">
                    <!-- Email input -->
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ $user->email }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div class="mb-4">
                    <!-- Password input (optional) -->
                    <label for="password" class="block text-sm font-medium text-gray-700">Password (leave blank to keep current)</label>
                    <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="mb-4">
                    <!-- Role selection -->
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="supplier" {{ $user->role === 'supplier' ? 'selected' : '' }}>Supplier</option>
                        <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="bakery_manager" {{ $user->role === 'bakery_manager' ? 'selected' : '' }}>Bakery Manager</option>
                        <option value="distributor" {{ $user->role === 'distributor' ? 'selected' : '' }}>Distributor</option>
                        <option value="retail_manager" {{ $user->role === 'retail_manager' ? 'selected' : '' }}>Retail Manager</option>
                    </select>
                </div>
                <div class="flex items-center justify-end">
                    <!-- Submit button -->
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection