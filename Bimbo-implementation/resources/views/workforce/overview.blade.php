@extends('layouts.bakery-manager')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Workforce Distribution Management
</h2>
@endsection

@section('content')
<!-- Distribution Overview Banner -->
<div class="bg-gradient-to-r from-purple-500 via-pink-500 to-yellow-500 rounded-lg shadow-lg mb-8">
    <div class="px-6 py-8">
        <div class="flex items-center justify-between">
            <div class="text-white">
                <h2 class="text-2xl font-bold mb-2">Distribution Overview</h2>
                <p class="text-pink-100">Monitor staff assignment, shifts, and availability across centers</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-24 h-24 text-pink-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l4 7h7l-5.5 4 2 7-5.5-4-5.5 4 2-7L1 9h7z" />
                </svg>
            </div>
        </div>
    </div>
</div>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <a href="{{ route('bakery.workforce.assignment', ['supply_center_id' => request('supply_center_id', session('supply_center_id'))]) }}" class="bg-gradient-to-r from-green-400 to-green-600 rounded-xl shadow-lg p-6 flex flex-col items-center hover:scale-105 transition-transform">
                    <svg class="w-10 h-10 mb-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
                    </svg>
                    <span class="text-lg font-semibold text-white">Staff Assignment</span>
                    <span class="text-sm text-green-100 mt-1">Assign staff to centers</span>
                </a>
                <a href="{{ route('bakery.workforce.shifts', ['supply_center_id' => request('supply_center_id', session('supply_center_id'))]) }}" class="bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl shadow-lg p-6 flex flex-col items-center hover:scale-105 transition-transform">
                    <svg class="w-10 h-10 mb-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-lg font-semibold text-white">Shift Scheduling</span>
                    <span class="text-sm text-blue-100 mt-1">Plan staff shifts</span>
                </a>
                <a href="{{ route('bakery.workforce.availability', ['supply_center_id' => request('supply_center_id', session('supply_center_id'))]) }}" class="bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-xl shadow-lg p-6 flex flex-col items-center hover:scale-105 transition-transform">
                    <svg class="w-10 h-10 mb-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-lg font-semibold text-white">Staff Availability</span>
                    <span class="text-sm text-yellow-100 mt-1">Check who is available</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection