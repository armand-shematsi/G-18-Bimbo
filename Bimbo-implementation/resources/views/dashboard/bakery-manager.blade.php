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

@section('navigation-links')
<a href="{{ route('bakery.production') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-sky-700 hover:border-sky-400 focus:outline-none focus:text-sky-700 focus:border-sky-400 transition">
    Production Monitoring
</a>
<a href="{{ route('bakery.schedule') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-sky-700 hover:border-sky-400 focus:outline-none focus:text-sky-700 focus:border-sky-400 transition">
    Workforce Schedule
</a>
<a href="{{ route('bakery.maintenance') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-sky-700 hover:border-sky-400 focus:outline-none focus:text-sky-700 focus:border-sky-400 transition">
    Machine Maintenance
</a>
@endsection

@section('content')
<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-sky-400 to-sky-200 rounded-2xl shadow-xl mb-10 overflow-hidden flex flex-col md:flex-row items-center justify-between px-8 py-10 relative">
    <div class="text-white z-10">
        <h2 class="text-3xl md:text-4xl font-extrabold mb-2 drop-shadow">Welcome, Bakery Manager!</h2>
        <p class="text-lg text-sky-100 font-medium">Monitor production, manage workforce, and keep your bakery running smoothly.</p>
    </div>
    <div class="hidden md:block absolute right-8 top-1/2 -translate-y-1/2 opacity-30 z-0">
        <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
        </svg>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-6 mb-12">
    <!-- Card Example: repeat for each stat -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-blue-400 flex flex-col items-start hover:shadow-2xl transition-shadow duration-200 group">
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
    <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-green-400 flex flex-col items-start hover:shadow-2xl transition-shadow duration-200 group">
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
    <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-cyan-400 flex flex-col items-start hover:shadow-2xl transition-shadow duration-200 group">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-semibold text-gray-600">Staff on Duty</p>
                <p class="text-2xl font-extrabold text-gray-900 live-staff-on-duty">{{ $staffOnDuty ?? '-' }}</p>
                <p class="text-xs text-gray-500">Currently present</p>
                <button class="text-xs text-blue-500 hover:underline mt-1" onclick="openDistributionModal()">View Distribution</button>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-red-400 flex flex-col items-start hover:shadow-2xl transition-shadow duration-200 group">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-1.414-1.414L12 9.172 7.05 4.222l-1.414 1.414L10.828 12l-5.192 5.192 1.414 1.414L12 14.828l4.95 4.95 1.414-1.414L13.172 12z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-semibold text-gray-600">Absence</p>
                <p class="text-2xl font-extrabold text-gray-900 live-absent-count">{{ $absentCount ?? '-' }}</p>
                <p class="text-xs text-gray-500">Staff absent today</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-emerald-400 flex flex-col items-start hover:shadow-2xl transition-shadow duration-200 group">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-semibold text-gray-600">Shift Filled</p>
                <p class="text-2xl font-extrabold text-gray-900 live-shift-filled">{{ $shiftFilled ?? '-' }}</p>
                <p class="text-xs text-gray-500">Shifts fully staffed</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-orange-400 flex flex-col items-start hover:shadow-2xl transition-shadow duration-200 group">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-semibold text-gray-600">Overtime</p>
                <p class="text-2xl font-extrabold text-gray-900 live-overtime-count">{{ $overtimeCount ?? '-' }}</p>
                <p class="text-xs text-gray-500">Staff in overtime</p>
            </div>
        </div>
    </div>
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
                <a href="{{ route('bakery.maintenance') }}" class="flex items-center p-4 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg text-white w-full mb-2 hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-semibold">Maintain Machines</p>
                        <p class="text-xs text-yellow-100">Log Maintenance</p>
                    </div>
                </a>
                <a href="{{ route('bakery.order-processing') }}" class="flex items-center p-4 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg text-white w-full mb-2 hover:from-indigo-600 hover:to-indigo-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-semibold">Order Processing</p>
                        <p class="text-xs text-indigo-100">Place/Receive Orders</p>
                    </div>
                </a>
                <a href="{{ route('workforce.overview') }}" class="flex items-center p-4 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg text-white w-full mb-2 hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-semibold">Workforce Distribution</p>
                        <p class="text-xs text-yellow-100">View & Manage Workforce</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Reports Center at the bottom, centered -->
<div class="flex justify-center mt-14 mb-10">
    <a href="{{ route('reports.downloads') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg text-xl transition-all duration-200">
        <i class="fas fa-file-alt mr-2"></i> Reports
    </a>
</div>

<!-- Recent Activity Timeline -->
<div class="mt-8 bg-white rounded-2xl shadow-xl">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-900">Recent Activity</h3>
    </div>
    <div class="p-6">
        <div class="flow-root">
            <ul class="-mb-8 activity-timeline">
                <li class="relative pb-8">
                    <div class="relative flex space-x-3">
                        <div>
                            <span class="h-8 w-8 rounded-full bg-sky-400 flex items-center justify-center ring-8 ring-white">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                            <div>
                                <p class="text-sm text-gray-500">Dashboard accessed</p>
                            </div>
                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                <time>{{ now()->format('M d, H:i') }}</time>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css">
<script>
    // --- Live Production Monitoring ---
    function fetchProductionLive() {
        const tbody = document.querySelector('.production-batch-tbody');
        tbody.innerHTML = `<tr><td colspan='6' class='text-center text-gray-400 py-4'>Loading...</td></tr>`;
        fetch("{{ route('bakery.bakery.production-live') }}")
            .then(res => res.json())
            .then(data => {
                tbody.innerHTML = '';
                if (!data.batches || data.batches.length === 0) {
                    tbody.innerHTML = `<tr><td colspan='6' class='text-center text-gray-400 py-4'>No batches found.</td></tr>`;
                } else {
                    data.batches.forEach(batch => {
                        function fmt(dt) {
                            if (!dt) return '-';
                            const d = new Date(dt);
                            if (isNaN(d)) return dt;
                            return d.toLocaleString('en-US', {
                                month: '2-digit',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                        }
                        let badgeClass = 'bg-gray-200 text-gray-800';
                        if (batch.status === 'Active') badgeClass = 'bg-blue-200 text-blue-800';
                        if (batch.status === 'Completed') badgeClass = 'bg-green-200 text-green-800';
                        if (batch.status === 'Delayed') badgeClass = 'bg-red-200 text-red-800';
                        tbody.innerHTML += `<tr>
                            <td>${batch.name}</td>
                            <td><span class='px-2 py-1 rounded ${badgeClass}'>${batch.status}</span></td>
                            <td>${fmt(batch.scheduled_start)}</td>
                            <td>${fmt(batch.actual_start)}</td>
                            <td>${fmt(batch.actual_end)}</td>
                            <td title='${batch.notes ?? ''}'>${batch.notes ? batch.notes.substring(0, 30) + (batch.notes.length > 30 ? '...' : '') : '-'}</td>
                        </tr>`;
                    });
                }
            });
    }
    fetchProductionLive();
    setInterval(fetchProductionLive, 2000);

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
        fetchProductionLive();
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

    function openDistributionModal() {
        document.getElementById('distributionModal').classList.remove('hidden');
        fetch('/api/workforce-distribution')
            .then(res => res.json())
            .then(data => {
                const content = document.getElementById('distributionContent');
                if (!data.length) {
                    content.innerHTML = '<p class="text-gray-500 text-center">No distribution data available.</p>';
                    return;
                }
                let html = '<table class="min-w-full text-sm"><thead><tr><th class="px-4 py-2 text-left">Center</th><th class="px-4 py-2 text-left">Staff</th></tr></thead><tbody>';
                data.forEach(center => {
                    html += `<tr><td class='px-4 py-2 font-bold'>${center.name}</td><td class='px-4 py-2'>`;
                    if (center.users && center.users.length) {
                        html += center.users.map(u => `${u.name} (${u.role})`).join(', ');
                    } else {
                        html += '<span class="text-gray-400">None</span>';
                    }
                    html += '</td></tr>';
                });
                html += '</tbody></table>';
                content.innerHTML = html;
            })
            .catch(() => {
                document.getElementById('distributionContent').innerHTML = '<p class="text-red-500 text-center">Failed to load data.</p>';
            });
    }

    function closeDistributionModal() {
        document.getElementById('distributionModal').classList.add('hidden');
    }
</script>
@endpush

<!-- Product Gallery -->
@include('components.product-gallery')