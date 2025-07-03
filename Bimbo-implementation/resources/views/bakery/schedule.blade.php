@extends('layouts.bakery-manager')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Workforce Schedule</h1>
        <p class="mt-1 text-sm text-gray-600">Manage and optimize your bakery staff shifts and assignments.</p>
    </div>
    <div class="text-right">
        <p class="text-sm text-gray-500">Last updated</p>
        <p class="text-sm font-medium text-gray-900">{{ now()->format('M d, Y H:i') }}</p>
    </div>
</div>
@endsection

@section('content')
<!-- Banner -->
<div class="bg-gradient-to-r from-green-500 via-blue-500 to-purple-500 rounded-lg shadow-lg mb-8">
    <div class="px-6 py-8 flex items-center justify-between">
        <div class="text-white">
            <h2 class="text-2xl font-bold mb-2">Workforce Overview</h2>
            <p class="text-green-100">Monitor staff, assign shifts, and handle absences</p>
        </div>
        <div class="hidden md:block">
            <svg class="w-24 h-24 text-green-200" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
            </svg>
        </div>
    </div>
</div>
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Staff on Duty</p>
            <p class="text-2xl font-bold text-gray-900 staff-on-duty">-</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Absences</p>
            <p class="text-2xl font-bold text-gray-900 absences-today">-</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Shifts Filled</p>
            <p class="text-2xl font-bold text-gray-900 shifts-filled">-</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Overtime</p>
            <p class="text-2xl font-bold text-gray-900 overtime-today">-</p>
        </div>
    </div>
</div>
<!-- Quick Actions -->
<div class="mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4">
        <button onclick="openAssignTaskModal()" class="flex-1 flex items-center p-4 bg-gradient-to-r from-green-500 to-green-600 rounded-lg text-white hover:from-green-600 hover:to-green-700 transition-all duration-200 transform hover:scale-105">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="font-medium">Assign Shifts</p>
                <p class="text-xs text-green-100">Schedule Workforce</p>
            </div>
        </button>
        <button onclick="autoReassignTasks()" class="flex-1 flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="font-medium">Auto-Reassign</p>
                <p class="text-xs text-blue-100">Handle Absences</p>
            </div>
        </button>
        <a href="{{ route('admin.users.index') }}" class="flex-1 flex items-center p-4 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg text-white hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200 transform hover:scale-105">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="font-medium">View All Staff</p>
                <p class="text-xs text-yellow-100">Staff Directory</p>
            </div>
        </a>
    </div>
</div>
<!-- Workforce Table -->
<div class="bg-white rounded-xl shadow-lg mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Workforce Schedule</h3>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th>Title</th>
                        <th>Worker</th>
                        <th>Shift</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="task-list-tbody">
                    <tr>
                        <td colspan="5" class="text-center text-gray-400 py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No tasks scheduled</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Absence/Availability Alerts -->
<div class="bg-white rounded-xl shadow-lg mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Absence & Availability Alerts</h3>
    </div>
    <div class="p-6">
        <ul class="list-disc pl-5 text-sm text-gray-700">
            <li>No absences reported today.</li>
        </ul>
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
                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                            <div>
                                <p class="text-sm text-gray-500">Workforce dashboard accessed</p>
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
@endsection

@push('scripts')
<script>
    // Fetch and update stats cards
    function fetchWorkforceStats() {
        fetch('/api/workforce-analytics')
            .then(res => res.json())
            .then(data => {
                document.querySelector('.staff-on-duty').textContent = data.staff_on_duty ?? '-';
                document.querySelector('.absences-today').textContent = data.absences ?? '-';
                document.querySelector('.shifts-filled').textContent = data.shifts_filled ?? '-';
                document.querySelector('.overtime-today').textContent = data.overtime ?? '-';
            });
    }
    // Fetch and update workforce table
    function fetchWorkforceTasks() {
        fetch('/bakery/workforce/tasks')
            .then(res => res.json())
            .then(data => {
                const tbody = document.querySelector('.task-list-tbody');
                tbody.innerHTML = '';
                if (!data.length) {
                    tbody.innerHTML = `<tr><td colspan='5' class='text-center text-gray-400 py-8'>
                    <svg class='mx-auto h-12 w-12 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'></path>
                    </svg>
                    <p class='mt-2 text-sm text-gray-500'>No tasks scheduled</p>
                </td></tr>`;
                } else {
                    data.forEach(task => {
                        tbody.innerHTML += `<tr>
                        <td>${task.title}</td>
                        <td>${task.user ? task.user.name : ''}</td>
                        <td>${task.shift ? task.shift.name : ''}</td>
                        <td>${task.status.replace('_', ' ')}</td>
                        <td>
                            <select onchange="updateTaskStatus(${task.id}, this.value)">
                                <option value="pending" ${task.status === 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="in_progress" ${task.status === 'in_progress' ? 'selected' : ''}>In Progress</option>
                                <option value="completed" ${task.status === 'completed' ? 'selected' : ''}>Completed</option>
                                <option value="reassigned" ${task.status === 'reassigned' ? 'selected' : ''}>Reassigned</option>
                            </select>
                        </td>
                    </tr>`;
                    });
                }
            });
    }
    // Initial fetch and polling
    fetchWorkforceStats();
    fetchWorkforceTasks();
    setInterval(fetchWorkforceStats, 10000);
    setInterval(fetchWorkforceTasks, 10000);
</script>
@endpush