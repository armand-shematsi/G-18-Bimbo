@extends('layouts.bakery-manager')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Bakery Manager Dashboard</h1>
        <p class="mt-1 text-sm text-gray-600">Welcome back, {{ auth()->user()->name ?? 'Bakery Manager' }}! Here's your bakery overview.</p>
    </div>
    <div class="text-right">
        <p class="text-sm text-gray-500">Last updated</p>
        <p class="text-sm font-medium text-gray-900">{{ now()->format('M d, Y H:i') }}</p>
    </div>
</div>
@endsection

@section('navigation-links')
<a href="{{ route('bakery.production') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
    Production Monitoring
</a>
<a href="{{ route('bakery.schedule') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
    Workforce Schedule
</a>
<a href="{{ route('bakery.maintenance') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
    Machine Maintenance
</a>
@endsection

@section('content')
<!-- New/Assigned Orders Section -->
<div class="bg-white rounded-xl shadow-lg mb-8 border-l-4 border-blue-600">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-900">New & Assigned Orders</h2>
        <span class="text-sm text-gray-500">(Pending & Processing)</span>
    </div>
    <div class="p-6">
        @if($orders->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">Order ID</th>
                            <th class="px-4 py-2 text-left font-semibold">Customer</th>
                            <th class="px-4 py-2 text-left font-semibold">Status</th>
                            <th class="px-4 py-2 text-left font-semibold">Placed At</th>
                            <th class="px-4 py-2 text-left font-semibold">Fulfillment</th>
                            <th class="px-4 py-2 text-left font-semibold">Delivery</th>
                            <th class="px-4 py-2 text-left font-semibold">Tracking #</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $order->id }}</td>
                            <td class="px-4 py-2">{{ $order->customer_name }}</td>
                            <td class="px-4 py-2 capitalize">{{ $order->status }}</td>
                            <td class="px-4 py-2">{{ $order->placed_at ? $order->placed_at->format('M d, Y H:i') : '-' }}</td>
                            <td class="px-4 py-2">{{ $order->fulfillment_type ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $order->delivery_option ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $order->tracking_number ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center text-gray-500 py-8">
                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p class="mt-2 text-sm">No new or assigned orders at the moment.</p>
            </div>
        @endif
    </div>
</div>

<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-pink-500 via-red-500 to-yellow-500 rounded-lg shadow-lg mb-8">
    <div class="px-6 py-8">
        <div class="flex items-center justify-between">
            <div class="text-white">
                <h2 class="text-2xl font-bold mb-2">Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }}, {{ auth()->user()->name ?? 'Bakery Manager' }}!</h2>
                <p class="text-pink-100">Monitor production, manage workforce, and keep your bakery running smoothly</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-24 h-24 text-pink-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Today's Output -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Today's Output</p>
                <p class="text-2xl font-bold text-gray-900 production-output">-</p>
                <p class="text-xs text-gray-500">Loaves produced</p>
            </div>
        </div>
    </div>
    <!-- Production Target -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Production Target</p>
                <p class="text-2xl font-bold text-gray-900 production-target">-</p>
                <p class="text-xs text-gray-500">Target for today</p>
            </div>
        </div>
    </div>
    <!-- Active Staff -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Staff</p>
                <p class="text-2xl font-bold text-gray-900 active-staff">-</p>
                <p class="text-xs text-gray-500">On duty now</p>
            </div>
        </div>
    </div>
    <!-- Machines Running -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Machines Running</p>
                <p class="text-2xl font-bold text-gray-900 machines-running">-</p>
                <p class="text-xs text-gray-500">Ovens/mixers active</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Batches (2/3) -->
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white rounded-xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Batches</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th>Batch Name</th>
                                <th>Status</th>
                                <th>Scheduled Start</th>
                                <th>Actual Start</th>
                                <th>Actual End</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody class="production-batch-tbody">
                            <tr>
                                <td colspan="6" class="text-center text-gray-400 py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No recent batches</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Quick Actions & Alerts (1/3) -->
    <div class="lg:col-span-1 space-y-8">
        <div class="bg-white rounded-xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-6 space-y-4">
                <a href="{{ route('bakery.production') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white w-full mb-2 hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium">Start New Production</p>
                        <p class="text-xs text-blue-100">Start Batch</p>
                    </div>
                </a>
                <a href="{{ route('bakery.schedule') }}" class="flex items-center p-4 bg-gradient-to-r from-green-500 to-green-600 rounded-lg text-white w-full mb-2 hover:from-green-600 hover:to-green-700 transition-all duration-200 transform hover:scale-105">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium">Schedule Workforce</p>
                        <p class="text-xs text-green-100">Assign Shifts</p>
                    </div>
                </a>
                <a href="{{ route('bakery.maintenance') }}" class="flex items-center p-4 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg text-white w-full mb-2 hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200 transform hover:scale-105">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium">Maintain Machines</p>
                        <p class="text-xs text-yellow-100">Log Maintenance</p>
                    </div>
                </a>
            </div>
        </div>
        <!-- Ingredient Alerts and Machine Alerts removed; assign to their respective dashboards -->
    </div>
</div>

<!-- Activity Timeline -->
<div class="mt-8 bg-white rounded-xl shadow-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
    </div>
    <div class="p-6">
        <div class="flow-root">
            <ul class="-mb-8 activity-timeline">
                <li class="relative pb-8">
                    <div class="relative flex space-x-3">
                        <div>
                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
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

<!-- Assign Task Modal -->
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    // --- Live Machines ---
    function fetchMachinesLive() {
        fetch("{{ route('bakery.bakery.machines-live') }}")
            .then(res => res.json())
            .then(data => {
                const machineList = document.querySelector('.machine-status-list');
                machineList.innerHTML = '';
                data.machines.forEach(m => {
                    let color = m.status === 'Running' ? 'text-green-600' : (m.status === 'Maintenance' ? 'text-yellow-600' : 'text-red-600');
                    machineList.innerHTML += `<li>${m.name}: <span class='${color}'>${m.status}</span></li>`;
                });
                const alertList = document.querySelector('.machine-alert-list');
                alertList.innerHTML = '';
                data.alerts.forEach(alert => {
                    alertList.innerHTML += `<li>${alert}</li>`;
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
        fetchMachinesLive();
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

    function fetchRecentBatches() {
        const tbody = document.querySelector('.production-batch-tbody');
        tbody.innerHTML = `<tr><td colspan='6' class='text-center text-gray-400 py-8'>Loading...</td></tr>`;
        fetch('/bakery/api/production-batches')
            .then(res => res.json())
            .then(batches => {
                tbody.innerHTML = '';
                if (!batches || batches.length === 0) {
                    tbody.innerHTML = `<tr><td colspan='6' class='text-center text-gray-400 py-8'>
                        <svg class='mx-auto h-12 w-12 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'></path>
                        </svg>
                        <p class='mt-2 text-sm text-gray-500'>No recent batches</p>
                    </td></tr>`;
                } else {
                    batches.forEach(batch => {
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
                        if (batch.status === 'active' || batch.status === 'Active') badgeClass = 'bg-blue-200 text-blue-800';
                        if (batch.status === 'completed' || batch.status === 'Completed') badgeClass = 'bg-green-200 text-green-800';
                        if (batch.status === 'delayed' || batch.status === 'Delayed') badgeClass = 'bg-red-200 text-red-800';
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
    fetchRecentBatches();
    setInterval(fetchRecentBatches, 10000);
</script>
@endpush