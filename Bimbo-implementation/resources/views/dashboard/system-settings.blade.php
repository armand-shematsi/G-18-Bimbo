@extends('layouts.dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        System Settings
    </h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Manage System Settings</h3>
            <div class="mt-4">
                <form action="{{ route('system.settings.update') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="setting_name" class="block text-sm font-medium text-gray-700">Setting Name</label>
                            <input type="text" name="setting_name" id="setting_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="setting_value" class="block text-sm font-medium text-gray-700">Setting Value</label>
                            <input type="text" name="setting_value" id="setting_value" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Update Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 