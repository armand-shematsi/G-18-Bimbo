@extends('layouts.bakery-manager')

@section('header')
<div class="flex flex-col md:flex-row justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        <img src="/images/baguette.jpg" alt="Bakery Logo" class="w-12 h-12 rounded-full shadow-md border-2 border-sky-400 bg-white object-cover">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Bakery Manager Dashboard</h1>
            <p class="mt-1 text-base text-gray-600 font-medium">Welcome, Bakery Manager!</p>
        </div>
    </div>
    <div class="text-right mt-4 md:mt-0">
        <p class="text-sm text-gray-500">Last updated</p>
        <p class="text-sm font-medium text-gray-900">{{ now()->format('M d, Y H:i') }}</p>
    </div>
</div>
@endsection

@section('content')
<!-- Welcome Banner -->
<div class="bg-blue-500 rounded-2xl shadow-xl mb-10 overflow-hidden flex flex-col md:flex-row items-center justify-between px-8 py-10 relative">
    <div class="text-white z-10">
        <h2 class="text-3xl md:text-4xl font-extrabold mb-2 drop-shadow">Welcome, Bakery Manager!</h2>
        <p class="text-lg text-blue-100 font-medium">Monitor production, manage workforce, and keep your bakery running smoothly.</p>
    </div>
    <div class="hidden md:block absolute right-8 top-1/2 -translate-y-1/2 opacity-30 z-0">
        <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
        </svg>
    </div>
</div>

<!-- Statistics Cards -->
<div class="w-full overflow-x-auto">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6 mb-12">
        <!-- First Row -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-blue-400 flex flex-col items-start hover:shadow-2xl transition-shadow duration-200 group min-w-[220px]">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Today's Output</p>
                    <p class="text-2xl font-extrabold text-gray-900 production-output">-</p>
                    <p class="text-xs text-gray-500">Loaves produced</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-green-400 flex flex-col items-start hover:shadow-2xl transition-shadow duration-200 group min-w-[220px]">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Production Target</p>
                    <p class="text-2xl font-extrabold text-gray-900 production-target">-</p>
                    <p class="text-xs text-gray-500">Target for today</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-cyan-400 flex flex-col items-start hover:shadow-2xl transition-shadow duration-200 group min-w-[220px]">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Staff on Duty</p>
                    <p class="text-2xl font-extrabold text-gray-900 live-staff-on-duty">0</p>
                </div>
            </div>
        </div>
        <!-- Second Row -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-red-400 flex flex-col items-start hover:shadow-2xl transition-shadow duration-200 group min-w-[220px]">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-1.414-1.414L12 9.172 7.05 4.222l-1.414 1.414L10.828 12l-5.192 5.192 1.414 1.414L12 14.828l4.95 4.95 1.414-1.414L13.172 12z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Absence</p>
                    <p class="text-2xl font-extrabold text-gray-900 live-absent-count">-</p>
                    <p class="text-xs text-gray-500">Staff absent today</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-emerald-400 flex flex-col items-start hover:shadow-2xl transition-shadow duration-200 group min-w-[220px]">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Shift Filled</p>
                    <p class="text-2xl font-extrabold text-gray-900 live-shift-filled">-</p>
                    <p class="text-xs text-gray-500">Shifts fully staffed</p>
                </div>
            </div>
        </div>

    </div>
</div>


<!-- Main Content Grid -->
<!-- Removed Production Overview widget and all related code as requested -->

<!-- Quick Actions & Alerts -->
<div class="space-y-6">
    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-xl">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-6 space-y-4">
            <a href="{{ route('bakery.production') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white w-80 hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold">Start New Production</p>
                    <p class="text-xs text-blue-100">Start Batch</p>
                </div>
            </a>

            <a href="{{ route('bakery.maintenance') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white w-80 hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold">Maintain Machines</p>
                    <p class="text-xs text-blue-100">Log Maintenance</p>
                </div>
            </a>

            <a href="{{ route('bakery.order-processing') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white w-80 hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold">Order Processing</p>
                    <p class="text-xs text-blue-100">Place/Receive Orders</p>
                </div>
            </a>

            <a href="{{ route('supplier.raw-materials.catalog') }}" class="flex items-center p-4 bg-gradient-to-r from-green-500 to-blue-600 rounded-lg text-white w-80 hover:from-blue-600 hover:to-green-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold">Order Raw Materials</p>
                    <p class="text-xs text-blue-100">Browse & Order</p>
                </div>
            </a>

            <a href="{{ route('workforce.overview') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white w-80 hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold">Workforce Distribution</p>
                    <p class="text-xs text-blue-100">View & Manage Workforce</p>
                </div>
            </a>

            <button onclick="openStaffAssignmentCalendar()" class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white w-80 hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold">Staff Assignment Calendar</p>
                    <p class="text-xs text-blue-100">Daily Assignment View</p>
                </div>
            </button>
        </div>
    </div>

<!-- New & Assigned Orders Section -->
<div class="bg-white rounded-xl shadow-lg p-6 mt-8">
    <h2 class="text-xl font-bold text-blue-700 mb-4">New & Assigned Orders</h2>
    @if(isset($orders) && $orders->count())
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left font-semibold">Order #</th>
                    <th class="px-4 py-2 text-left font-semibold">Status</th>
                    <th class="px-4 py-2 text-left font-semibold">Retailer</th>
                    <th class="px-4 py-2 text-left font-semibold">Product(s)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td class="px-4 py-2">{{ $order->id }}</td>
                        <td class="px-4 py-2">{{ $order->status }}</td>
                        <td class="px-4 py-2">{{ $order->user->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            @foreach($order->items as $item)
                                {{ $item->product->name ?? 'N/A' }} ({{ $item->quantity }})<br>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="text-gray-500">No new or assigned orders at the moment.</div>
    @endif
</div>

<!-- Main Content Grid -->
<div class="flex flex-col lg:flex-row gap-8">
    <!-- Main Content (left) -->
    <div class="flex-1">
        <!-- (Reserved for future bakery widgets, charts, or news) -->
    </div>
    <!-- Quick Actions (right) -->
    <div class="w-full lg:w-1/3 xl:w-1/4 lg:self-start">
        <div class="bg-white rounded-2xl shadow-xl">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-6 space-y-4">
                <a href="{{ route('bakery.production') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white w-full mb-2 hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-semibold">Start New Production</p>
                        <p class="text-xs text-blue-100">Start Batch</p>
                    </div>
                </a>
                <a href="{{ route('bakery.maintenance') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white w-full mb-2 hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-semibold">Maintain Machines</p>
                        <p class="text-xs text-blue-100">Log Maintenance</p>
                    </div>
                </a>
                <a href="{{ route('bakery.order-processing') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white w-full mb-2 hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-semibold">Order Processing</p>
                        <p class="text-xs text-blue-100">Place/Receive Orders</p>
                    </div>
                </a>
                <a href="{{ route('supplier.raw-materials.catalog') }}" class="flex items-center p-4 bg-gradient-to-r from-green-500 to-blue-600 rounded-lg text-white w-full mb-2 hover:from-blue-600 hover:to-green-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-semibold">Order Raw Materials</p>
                        <p class="text-xs text-blue-100">Browse & Order</p>
                    </div>
                </a>
                <a href="{{ route('workforce.overview') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white w-full mb-2 hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-semibold">Workforce Distribution</p>
                        <p class="text-xs text-blue-100">View & Manage Workforce</p>
                    </div>
                </a>
            </div>
        </div>
        <!-- Reports Center -->
        <div class="bg-gradient-to-r from-sky-100 to-white rounded-2xl shadow-xl border-2 border-sky-400 p-8 mb-8">
            <div class="flex items-center mb-6">
                <svg class="w-12 h-12 text-sky-400 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4m0 0V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2h6" />
                </svg>
                <div>
                    <h4 class="text-2xl font-extrabold text-sky-600">Reports Center</h4>
                    <p class="text-sky-600 font-medium">Access all your daily and weekly reports in one place.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('reports.downloads') }}" class="flex items-center justify-center p-4 bg-sky-500 hover:bg-sky-600 text-white rounded-lg font-bold text-lg shadow-md transition-all duration-200 transform hover:scale-105">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View Your Reports
                </a>
                <a href="{{ route('reports.downloads') }}" class="flex items-center justify-center p-4 bg-green-500 hover:bg-green-600 text-white rounded-lg font-bold text-lg shadow-md transition-all duration-200 transform hover:scale-105">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Your Reports
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Assign Task Modal (unchanged) -->
<div id="assignTaskModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Assign Task</h3>
        <form id="assignTaskForm">
            <div class="mb-2">
                <label class="block text-sm font-medium">Title</label>
                <input type="text" name="title" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" class="w-full border rounded px-3 py-2"></textarea>
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium">Worker</label>
                <select name="user_id" class="w-full border rounded px-3 py-2" required></select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Shift</label>
                <select name="shift_id" class="w-full border rounded px-3 py-2"></select>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeAssignTaskModal()" class="mr-2 px-4 py-2 rounded bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white">Assign</button>
            </div>
        </form>
    </div>
</div>
<!-- Workforce Distribution Modal -->
<div id="distributionModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl relative">
        <button onclick="closeDistributionModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
        <h3 class="text-lg font-semibold mb-4">Workforce Distribution (Live)</h3>
        <div id="distributionContent">
            <p class="text-gray-500 text-center">Loading...</p>
        </div>
    </div>
</div>

<!-- Staff Assignment Calendar Modal -->
<div id="staffAssignmentCalendarModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-4xl relative">
        <button onclick="closeStaffAssignmentCalendar()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Staff Assignment Calendar</h3>
            <p class="text-gray-600">View and manage daily staff assignments</p>
        </div>

        <!-- Date Selection -->
        <div class="mb-6">
            <label for="assignment-date" class="block text-sm font-semibold text-gray-700 mb-2">Select Date</label>
            <input type="date" id="assignment-date" value="{{ now()->toDateString() }}"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Assignment List -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Assignments for <span id="selected-date">{{ now()->format('M d, Y') }}</span></h4>
            <div id="assignment-list" class="space-y-3">
                <p class="text-gray-500 text-center py-8">Loading assignments...</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-6 flex gap-3">
            <button onclick="refreshAssignments()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh
            </button>
            <button onclick="exportAssignments()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export
            </button>
        </div>
    </div>
</div>
@endsection

<script>
    console.log("Dashboard JS loaded");

    // Show a status message on the dashboard for debugging
    function showStatus(msg, isError = false) {
        let el = document.getElementById('dashboard-status-msg');
        if (!el) {
            el = document.createElement('div');
            el.id = 'dashboard-status-msg';
            el.style.position = 'fixed';
            el.style.bottom = '10px';
            el.style.right = '10px';
            el.style.background = isError ? '#ffdddd' : '#ddffdd';
            el.style.color = isError ? '#a00' : '#070';
            el.style.padding = '10px 20px';
            el.style.border = '1px solid #aaa';
            el.style.zIndex = 9999;
            document.body.appendChild(el);
        }
        el.textContent = msg;
    }

    // --- Live Workforce ---
    function fetchWorkforceLive() {
        fetch("{{ route('bakery.bakery.workforce-live') }}")
            .then(res => res.json())
            .then(data => {
                const staffList = document.querySelector('.workforce-staff-list');
                staffList.innerHTML = '';
                data.staff.forEach(staff => {
                    staffList.innerHTML += `<li>${staff.name} (${staff.role})</li>`;
                });
                const assignList = document.querySelector('.workforce-assign-list');
                assignList.innerHTML = '';
                data.assignments.forEach(a => {
                    assignList.innerHTML += `<li>${a.staff} assigned to ${a.batch}.</li>`;
                });
            });
    }
    // --- Live Ingredients ---
    function fetchIngredientsLive() {
        fetch("{{ route('bakery.bakery.ingredients-live') }}")
            .then(res => res.json())
            .then(data => {
                const ingList = document.querySelector('.ingredient-list');
                ingList.innerHTML = '';
                data.ingredients.forEach(i => {
                    let alert = i.alert ? ` <span class='text-red-600'>(${i.alert})</span>` : '';
                    ingList.innerHTML += `<li>${i.name}: ${i.stock}kg${alert}</li>`;
                });
            });
    }
    // --- Live Notifications ---
    function fetchNotificationsLive() {
        fetch("{{ route('bakery.bakery.notifications-live') }}")
            .then(res => res.json())
            .then(data => {
                const notifLists = document.querySelectorAll('.notification-list');
                notifLists.forEach(list => {
                    list.innerHTML = '';
                    data.notifications.forEach(n => {
                        list.innerHTML += `<li>${n}</li>`;
                    });
                });
            });
    }
    // --- Live Chat ---
    function fetchChatLive() {
        fetch("{{ route('bakery.bakery.chat-live') }}")
            .then(res => res.json())
            .then(data => {
                const chatBox = document.querySelector('.chat-messages');
                chatBox.innerHTML = '';
                data.messages.forEach(m => {
                    chatBox.innerHTML += `<div><span class='font-bold'>${m.user}:</span> ${m.message}</div>`;
                });
            });
    }
    // Initial fetch and polling
    function fetchAllLive() {
        fetchWorkforceLive();
        fetchIngredientsLive();
        fetchNotificationsLive();
        fetchChatLive();
        fetchStaffOnDuty();
    }
    fetchAllLive();
    setInterval(fetchAllLive, 15000);

    // --- Workforce Management AJAX ---
    function fetchTasks() {
        fetch("{{ route('bakery.workforce.tasks') }}")
            .then(res => res.json())
            .then(data => {
                const tbody = document.querySelector('.task-list-tbody');
                tbody.innerHTML = '';
                data.forEach(task => {
                    tbody.innerHTML += `<tr>
                <td class='px-4 py-3'>${task.title}</td>
                <td class='px-4 py-3'>${task.user ? task.user.name : ''}</td>
                <td class='px-4 py-3'>${task.shift ? task.shift.name : ''}</td>
                <td class='px-4 py-3'>${task.status.replace('_', ' ')}</td>
                <td class='px-4 py-3'>
                    <select onchange="updateTaskStatus(${task.id}, this.value)">
                        <option value="pending" ${task.status === 'pending' ? 'selected' : ''}>Pending</option>
                        <option value="in_progress" ${task.status === 'in_progress' ? 'selected' : ''}>In Progress</option>
                        <option value="completed" ${task.status === 'completed' ? 'selected' : ''}>Completed</option>
                        <option value="reassigned" ${task.status === 'reassigned' ? 'selected' : ''}>Reassigned</option>
                    </select>
                </td>
            </tr>`;
                });
            });
    }

    function openAssignTaskModal() {
        document.getElementById('assignTaskModal').classList.remove('hidden');
        const userSelect = document.querySelector('#assignTaskForm select[name=user_id]');
        const shiftSelect = document.querySelector('#assignTaskForm select[name=shift_id]');
        userSelect.innerHTML = '<option>Loading...</option>';
        shiftSelect.innerHTML = '<option>Loading...</option>';
        // Fetch only available workers (not absent/on leave)
        fetch('/api/staff-availability?week=' + new Date().toISOString().slice(0, 10))
            .then(res => res.json())
            .then(data => {
                // Only show workers present or unknown today
                const today = new Date().toISOString().slice(0, 10);
                const available = data.availability.filter(u => u.availability[today] === 'present' || u.availability[today] === 'unknown');
                if (available.length === 0) {
                    userSelect.innerHTML = '<option disabled>No available staff</option>';
                    document.querySelector('#assignTaskForm button[type=submit]').disabled = true;
                } else {
                    userSelect.innerHTML = available.map(u => `<option value="${u.id}">${u.name} (${u.role})</option>`).join('');
                    document.querySelector('#assignTaskForm button[type=submit]').disabled = false;
                }
            });
        fetch("/api/shifts")
            .then(res => res.json())
            .then(shifts => {
                shiftSelect.innerHTML = '<option value="">--None--</option>' + shifts.map(s => `<option value="${s.id}">${s.name}</option>`).join('');
            });
    }

    function closeAssignTaskModal() {
        document.getElementById('assignTaskModal').classList.add('hidden');
    }
    document.getElementById('assignTaskForm').onsubmit = function(e) {
        e.preventDefault();
        const form = e.target;
        const data = Object.fromEntries(new FormData(form));
        const submitBtn = form.querySelector('button[type=submit]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Assigning...';
        fetch("{{ route('bakery.workforce.assign-task') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        }).then(res => res.json()).then(() => {
            closeAssignTaskModal();
            fetchTasks();
            submitBtn.disabled = false;
            submitBtn.textContent = 'Assign';
        }).catch(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Assign';
            alert('Failed to assign task.');
        });
    };

    function updateTaskStatus(taskId, status) {
        fetch(`{{ url('bakery/workforce/update-task') }}/${taskId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status
            })
        }).then(res => res.json()).then(() => fetchTasks());
    }

    function autoReassignTasks() {
        const btn = document.querySelector('button[onclick="autoReassignTasks()"]');
        btn.disabled = true;
        btn.textContent = 'Reassigning...';
        fetch("{{ route('bakery.workforce.auto-reassign') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(res => res.json()).then(() => {
            fetchTasks();
            btn.disabled = false;
            btn.textContent = 'Auto-Reassign Absent';
        }).catch(() => {
            btn.disabled = false;
            btn.textContent = 'Auto-Reassign Absent';
            alert('Failed to auto-reassign.');
        });
    }

    function fetchStaffOnDuty() {
        fetch('/api/staff-on-duty')
            .then(res => res.json())
            .then(data => {
                const staffList = document.querySelector('.workforce-staff-list');
                staffList.innerHTML = `<li class='font-bold mb-1'>Staff on Duty: ${data.count}</li>`;
                data.staff.forEach(staff => {
                    staffList.innerHTML += `<li>${staff.name} (${staff.role})</li>`;
                });
            });
    }
    // Initial fetch
    fetchTasks();
    fetchStaffOnDuty();

    // --- Auto-Assign Staff Button Logic ---
    document.getElementById('autoAssignBtn').onclick = function() {
        const btn = this;
        btn.disabled = true;
        btn.textContent = 'Assigning...';
        fetch("{{ route('bakery.workforce.auto-assign') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    date: new Date().toISOString().slice(0, 10)
                })
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = 'Auto-Assign Staff';
                fetchStatsLive(); // <-- Instant update after auto-assign
                // Listen for custom event for real-time updates (future-proof)
                document.dispatchEvent(new CustomEvent('autoAssignCompleted'));
                if (data.success) {
                    window.location.href = '/workforce/distribution-overview';
                } else {
                    alert('Auto-assignment failed: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = 'Auto-Assign Staff';
                alert('Auto-assignment failed due to network or server error.');
            });
    };

    // Listen for custom event for real-time updates (for future WebSocket integration)
    document.addEventListener('autoAssignCompleted', fetchStatsLive);
    // --- Laravel Echo/Pusher real-time updates ---
    if (window.Echo) {
        window.Echo.channel('dashboard-stats')
            .listen('.staff.autoAssigned', (e) => {
                fetchStatsLive();
            });
    }
    // TODO: For true real-time, integrate Laravel Echo/Pusher and trigger fetchStatsLive() on broadcast event.

    // --- Live Stats for Dashboard Cards ---
    function fetchStatsLive() {
        // Fetch staff data from the staff API
        fetch('/api/staff')
            .then(res => res.json())
            .then(staffData => {
                // Calculate staff on duty (Present status)
                const staffOnDuty = staffData.filter(staff => staff.status === 'Present').length;
                // Calculate absent staff (Absent status)
                const absentCount = staffData.filter(staff => staff.status === 'Absent').length;

                // Update the dashboard cards
                document.querySelector('.live-staff-on-duty').textContent = staffOnDuty;
                document.querySelector('.live-absent-count').textContent = absentCount;

                // Add visual feedback for real-time updates
                const staffCard = document.querySelector('.live-staff-on-duty').closest('.bg-white');
                const absenceCard = document.querySelector('.live-absent-count').closest('.bg-white');

                // Flash effect to show update
                staffCard.style.transition = 'all 0.3s ease';
                absenceCard.style.transition = 'all 0.3s ease';
                staffCard.style.transform = 'scale(1.02)';
                absenceCard.style.transform = 'scale(1.02)';

                setTimeout(() => {
                    staffCard.style.transform = 'scale(1)';
                    absenceCard.style.transform = 'scale(1)';
                }, 300);
            })
            .catch(error => {
                console.error('Error fetching staff data:', error);
                // Fallback to existing API if staff API fails
                fetch("{{ route('bakery.bakery.stats-live') }}")
                    .then(res => res.json())
                    .then(data => {
                        document.querySelector('.live-staff-on-duty').textContent = data.staffOnDuty ?? '-';
                        document.querySelector('.live-absent-count').textContent = data.absentCount ?? '-';
                        document.querySelector('.live-shift-filled').textContent = data.shiftFilled ?? '-';
                    });
            });
    }
    fetchStatsLive();
    setInterval(fetchStatsLive, 5000); // Update every 5 seconds for more responsive updates

    // --- Real-time Production Stats ---
    function fetchProductionStatsLive() {
        fetch('/api/production-live')
            .then(res => res.json())
            .then(data => {
                document.querySelector('.production-output').textContent = data.output ?? '-';
                document.querySelector('.production-target').textContent = data.productionTarget ?? '-';
            });
    }
    fetchProductionStatsLive();
    setInterval(fetchProductionStatsLive, 10000);

    // --- Real-time Production Overview ---
    function fetchProductionOverview() {
        fetch('/api/production-overview')
            .then(res => res.json())
            .then(data => {
                document.getElementById('today-batches').textContent = data.todayBatches ?? 0;
                document.getElementById('completed-batches').textContent = data.completedBatches ?? 0;
                document.getElementById('in-progress-batches').textContent = data.inProgressBatches ?? 0;
            })
            .catch(error => {
                console.error('Error fetching production overview:', error);
                // Set default values if API fails
                document.getElementById('today-batches').textContent = '0';
                document.getElementById('completed-batches').textContent = '0';
                document.getElementById('in-progress-batches').textContent = '0';
            });
    }
    fetchProductionOverview();
    setInterval(fetchProductionOverview, 15000);

    // --- Real-time Alerts ---
    function fetchAlerts() {
        fetch('/api/alerts')
            .then(res => res.json())
            .then(data => {
                const alertsContainer = document.querySelector('.alerts-container');
                if (alertsContainer && data.alerts) {
                    alertsContainer.innerHTML = data.alerts.map(alert => `
                        <div class="flex items-start p-3 bg-${alert.bgColor}-50 rounded-lg border border-${alert.bgColor}-200">
                            <div class="w-8 h-8 bg-${alert.bgColor}-500 rounded-full flex items-center justify-center mr-3 mt-1">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    ${alert.icon}
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-${alert.bgColor}-900">${alert.title}</p>
                                <p class="text-xs text-${alert.bgColor}-600">${alert.description}</p>
                            </div>
                        </div>
                    `).join('');
                }
            })
            .catch(error => {
                console.error('Error fetching alerts:', error);
            });
    }
    fetchAlerts();
    setInterval(fetchAlerts, 30000);

    // --- Responsive improvements ---
    function handleResponsiveLayout() {
        const isMobile = window.innerWidth < 768;
        const isTablet = window.innerWidth >= 768 && window.innerWidth < 1024;

        // Adjust grid layout based on screen size
        const mainGrid = document.querySelector('.grid.grid-cols-1.lg\\:grid-cols-3');
        if (mainGrid) {
            if (isMobile) {
                mainGrid.classList.remove('lg:grid-cols-3');
                mainGrid.classList.add('grid-cols-1');
            } else if (isTablet) {
                mainGrid.classList.remove('lg:grid-cols-3');
                mainGrid.classList.add('grid-cols-2');
            } else {
                mainGrid.classList.remove('grid-cols-1', 'grid-cols-2');
                mainGrid.classList.add('lg:grid-cols-3');
            }
        }
    }

    // Call on load and resize
    handleResponsiveLayout();
    window.addEventListener('resize', handleResponsiveLayout);

    // --- Smooth animations for cards ---
    function addCardAnimations() {
        const cards = document.querySelectorAll('.bg-white.rounded-2xl');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';
            });
        });
    }

    // Initialize animations after DOM loads
    document.addEventListener('DOMContentLoaded', function() {
        addCardAnimations();
        updateBakeryStats();
        setInterval(updateBakeryStats, 10000);

        // Set up real-time staff monitoring
        setupStaffMonitoring();
    });

    // Function to set up real-time staff monitoring
    function setupStaffMonitoring() {
        // Monitor for staff status changes
        let lastStaffData = null;

        function checkStaffChanges() {
            fetch('/api/staff')
                .then(res => res.json())
                .then(staffData => {
                    if (lastStaffData) {
                        // Check if any staff status has changed
                        const hasChanges = staffData.some((staff, index) => {
                            const lastStaff = lastStaffData[index];
                            return lastStaff && staff.status !== lastStaff.status;
                        });

                        if (hasChanges) {
                            console.log('Staff status changes detected, updating dashboard...');
                            updateBakeryStats();

                            // Trigger visual feedback
                            const staffCard = document.querySelector('.live-staff-on-duty').closest('.bg-white');
                            const absenceCard = document.querySelector('.live-absent-count').closest('.bg-white');

                            staffCard.style.backgroundColor = '#fef3c7';
                            absenceCard.style.backgroundColor = '#fef3c7';

                            setTimeout(() => {
                                staffCard.style.backgroundColor = '';
                                absenceCard.style.backgroundColor = '';
                            }, 1000);
                        }
                    }
                    lastStaffData = staffData;
                })
                .catch(error => {
                    console.error('Error monitoring staff changes:', error);
                });
        }

        // Check for changes every 3 seconds
        setInterval(checkStaffChanges, 3000);
    }
</script>

<script>
    function updateBakeryStats() {
        // Production stats
        fetch('/bakery/production-stats-live')
            .then(res => res.json())
            .then(data => {
                document.querySelector('.production-output').textContent = data.todaysOutput ?? '-';
                document.querySelector('.production-target').textContent = data.productionTarget ?? '-';
            });
        // Workforce stats - use staff API for real-time updates
        fetch('/api/staff')
            .then(res => res.json())
            .then(staffData => {
                // Calculate staff on duty (Present status)
                const staffOnDuty = staffData.filter(staff => staff.status === 'Present').length;
                // Calculate absent staff (Absent status)
                const absentCount = staffData.filter(staff => staff.status === 'Absent').length;

                // Update the dashboard cards
                document.querySelector('.live-staff-on-duty').textContent = staffOnDuty;
                document.querySelector('.live-absent-count').textContent = absentCount;

                // Add visual feedback for real-time updates
                const staffCard = document.querySelector('.live-staff-on-duty').closest('.bg-white');
                const absenceCard = document.querySelector('.live-absent-count').closest('.bg-white');

                // Flash effect to show update
                staffCard.style.transition = 'all 0.3s ease';
                absenceCard.style.transition = 'all 0.3s ease';
                staffCard.style.transform = 'scale(1.02)';
                absenceCard.style.transform = 'scale(1.02)';

                setTimeout(() => {
                    staffCard.style.transform = 'scale(1)';
                    absenceCard.style.transform = 'scale(1)';
                }, 300);
            })
            .catch(error => {
                console.error('Error fetching staff data:', error);
                // Fallback to existing API if staff API fails
                fetch('/bakery/stats-live')
                    .then(res => res.json())
                    .then(data => {
                        document.querySelector('.live-staff-on-duty').textContent = data.staffOnDuty ?? '-';
                        document.querySelector('.live-absent-count').textContent = data.absentCount ?? '-';
                        document.querySelector('.live-shift-filled').textContent = data.shiftFilled ?? '-';
                    });
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateBakeryStats();
        setInterval(updateBakeryStats, 10000); // every 10 seconds

        // Listen for staff status changes from workforce management
        window.addEventListener('staffStatusUpdated', function() {
            console.log('Staff status updated, refreshing dashboard stats...');
            updateBakeryStats();
        });

        // Listen for custom events that might be triggered from other pages
        window.addEventListener('staffAdded', updateBakeryStats);
        window.addEventListener('staffDeleted', updateBakeryStats);
        window.addEventListener('staffEdited', updateBakeryStats);
    });

    // --- Real-time Shift Filled Card ---
    function fetchShiftFilledLive() {
        console.log('Fetching shift filled data...');
        fetch('/api/assignments/filled-count')
            .then(res => {
                console.log('Response status:', res.status);
                if (!res.ok) {
                    throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                }
                return res.json();
            })
            .then(data => {
                console.log('Shift filled data:', data);
                const el = document.querySelector('.live-shift-filled');
                const card = el.closest('.bg-white');
                const prev = el.textContent;
                el.textContent = `${data.filled} / ${data.total}`;
                console.log('Updated shift filled card:', el.textContent);
                // Flash effect if value changes
                if (prev !== el.textContent) {
                    card.style.transition = 'all 0.3s';
                    card.style.transform = 'scale(1.03)';
                    setTimeout(() => {
                        card.style.transform = 'scale(1)';
                    }, 300);
                }
            })
            .catch((error) => {
                console.error('Error fetching shift filled data from filled-count endpoint:', error);
                console.log('Trying fallback to regular assignments API...');

                // Fallback: use regular assignments API
                fetch('/api/assignments')
                    .then(res => {
                        console.log('Fallback response status:', res.status);
                        if (!res.ok) {
                            throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                        }
                        return res.json();
                    })
                    .then(assignments => {
                        console.log('Fallback assignments data:', assignments);
                        const filled = assignments.filter(a => a.status === 'Assigned').length;
                        const total = assignments.length;
                        const el = document.querySelector('.live-shift-filled');
                        const card = el.closest('.bg-white');
                        const prev = el.textContent;
                        el.textContent = `${filled} / ${total}`;
                        console.log('Updated shift filled card via fallback:', el.textContent);

                        // Flash effect if value changes
                        if (prev !== el.textContent) {
                            card.style.transition = 'all 0.3s';
                            card.style.transform = 'scale(1.03)';
                            setTimeout(() => {
                                card.style.transform = 'scale(1)';
                            }, 300);
                        }
                    })
                    .catch((fallbackError) => {
                        console.error('Error with fallback assignments API:', fallbackError);
                        // final fallback: show dash
                        const el = document.querySelector('.live-shift-filled');
                        el.textContent = '-';
                    });
            });
    }
    fetchShiftFilledLive();
    setInterval(fetchShiftFilledLive, 5000);
</script>