@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Customer Dashboard') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-semibold mb-4">Welcome, {{ Auth::user()->name }}!</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Chat with Suppliers -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h4 class="text-lg font-medium text-blue-900 mb-2">Chat with Suppliers</h4>
                        <p class="text-blue-700 mb-4">Get support and ask questions directly to suppliers.</p>
                        <a href="{{ route('customer.chat.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            Start Chat
                        </a>
                    </div>

                    <!-- View Orders -->
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h4 class="text-lg font-medium text-green-900 mb-2">My Orders</h4>
                        <p class="text-green-700 mb-4">View and track your order history.</p>
                        <a href="#" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                            View Orders
                        </a>
                    </div>

                    <!-- Profile -->
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <h4 class="text-lg font-medium text-purple-900 mb-2">Profile</h4>
                        <p class="text-purple-700 mb-4">Update your account information.</p>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
