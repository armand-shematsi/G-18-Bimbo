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
        <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-orange-400 flex flex-col items-start hover:shadow-2xl transition-shadow duration-200 group min-w-[220px]">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Overtime</p>
                    <p class="text-2xl font-extrabold text-gray-900 live-overtime-count">-</p>
                    <p class="text-xs text-gray-500">Staff in overtime</p>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Production Overview Widget -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Production Overview</h3>
                <div class="flex space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                        Active
                    </span>
                </div>
            </div>

            <!-- Production Status Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-600">Today's Batches</p>
                            <p class="text-2xl font-bold text-blue-900" id="today-batches">0</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600">Completed</p>
                            <p class="text-2xl font-bold text-green-900" id="completed-batches">0</p>
                        </div>
                        <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-orange-600">In Progress</p>
                            <p class="text-2xl font-bold text-orange-900" id="in-progress-batches">0</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Timeline -->
            <div class="space-y-4">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h4>
                <div class="space-y-3" id="recent-activity-list">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">White Bread Batch Started</p>
                            <p class="text-xs text-gray-500">2 minutes ago</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Started
                        </span>
                    </div>

                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Whole Wheat Batch Completed</p>
                            <p class="text-xs text-gray-500">15 minutes ago</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Completed
                        </span>
                    </div>

                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Maintenance Alert: Mixer #2</p>
                            <p class="text-xs text-gray-500">1 hour ago</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            Alert
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Alerts -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-xl">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-6 space-y-4">
                <a href="{{ route('bakery.production') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white w-full hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105 shadow-md">
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

                <a href="{{ route('bakery.maintenance') }}" class="flex items-center p-4 bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg text-white w-full hover:from-orange-600 hover:to-orange-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-semibold">Maintain Machines</p>
                        <p class="text-xs text-orange-100">Log Maintenance</p>
                    </div>
                </a>

                <a href="{{ route('bakery.order-processing') }}" class="flex items-center p-4 bg-gradient-to-r from-green-500 to-green-600 rounded-lg text-white w-full hover:from-green-600 hover:to-green-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-semibold">Order Processing</p>
                        <p class="text-xs text-green-100">Place/Receive Orders</p>
                    </div>
                </a>

                <a href="{{ route('workforce.overview') }}" class="flex items-center p-4 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg text-white w-full hover:from-purple-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-semibold">Workforce Distribution</p>
                        <p class="text-xs text-purple-100">View & Manage Workforce</p>
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
        fetch("{{ route('bakery.bakery.stats-live') }}")
            .then(res => res.json())
            .then(data => {
                document.querySelector('.live-staff-on-duty').textContent = data.staffOnDuty ?? '-';
                document.querySelector('.live-absent-count').textContent = data.absentCount ?? '-';
                document.querySelector('.live-shift-filled').textContent = data.shiftFilled ?? '-';
                document.querySelector('.live-overtime-count').textContent = data.overtimeCount ?? '-';
            });
    }
    fetchStatsLive();
    setInterval(fetchStatsLive, 10000);

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

    // --- Real-time Recent Activity ---
    function fetchRecentActivity() {
        fetch('/api/recent-activity')
            .then(res => res.json())
            .then(data => {
                const activityList = document.getElementById('recent-activity-list');
                if (activityList && data.activities) {
                    activityList.innerHTML = data.activities.map(activity => `
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-${activity.color}-500 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    ${activity.icon}
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">${activity.title}</p>
                                <p class="text-xs text-gray-500">${activity.time}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-${activity.color}-100 text-${activity.color}-800">
                                ${activity.status}
                            </span>
                        </div>
                    `).join('');
                }
            })
            .catch(error => {
                console.error('Error fetching recent activity:', error);
            });
    }
    fetchRecentActivity();
    setInterval(fetchRecentActivity, 20000);

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
    });
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
        // Workforce stats
        fetch('/bakery/stats-live')
            .then(res => res.json())
            .then(data => {
                document.querySelector('.live-staff-on-duty').textContent = data.staffOnDuty ?? '-';
                document.querySelector('.live-absent-count').textContent = data.absentCount ?? '-';
                document.querySelector('.live-shift-filled').textContent = data.shiftFilled ?? '-';
                document.querySelector('.live-overtime-count').textContent = data.overtimeCount ?? '-';
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateBakeryStats();
        setInterval(updateBakeryStats, 10000); // every 10 seconds
    });
</script>