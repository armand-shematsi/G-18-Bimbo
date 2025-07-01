@extends('layouts.bakery-manager')

@section('header')
Bakery Manager Dashboard
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
<div class="space-y-8">
    <!-- Production Monitoring Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Production Monitoring</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Overview Card -->
            <div class="bg-blue-50 rounded-lg p-4 flex flex-col items-center">
                <span class="text-2xl font-bold production-output">1,250</span>
                <span class="text-gray-600">Today's Output</span>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                    <div class="bg-blue-500 h-2.5 rounded-full" style="width: 83%"></div>
                </div>
                <span class="text-xs text-gray-500 mt-1 production-target">Target: 1,500</span>
            </div>
            <!-- Trends Chart -->
            <div class="bg-white rounded-lg p-4 flex flex-col items-center">
                <span class="text-gray-600 mb-2">Production Trends</span>
                <canvas id="productionTrendsChart" height="80"></canvas>
            </div>
            <!-- Quality Metrics -->
            <div class="bg-green-50 rounded-lg p-4 flex flex-col items-center">
                <span class="text-2xl font-bold text-green-600">98%</span>
                <span class="text-gray-600">Quality Score</span>
                <span class="text-xs text-gray-500 mt-1">Rejection Rate: 2%</span>
            </div>
        </div>
        <!-- Batch List -->
        <div class="mt-6">
            <h3 class="text-lg font-medium mb-2">Current & Upcoming Batches</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 w-48 text-left font-medium text-gray-500 uppercase tracking-wider">Batch Name</th>
                            <th class="px-4 py-3 w-32 text-left font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 w-32 text-left font-medium text-gray-500 uppercase tracking-wider">Start</th>
                            <th class="px-4 py-3 w-32 text-left font-medium text-gray-500 uppercase tracking-wider">End</th>
                            <th class="px-4 py-3 w-32 text-left font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 production-batch-tbody">
                        <tr>
                            <td class="px-4 py-3 w-48 truncate">Batch A</td>
                            <td class="px-4 py-3 w-32">Active</td>
                            <td class="px-4 py-3 w-32">08:00</td>
                            <td class="px-4 py-3 w-32">12:00</td>
                            <td class="px-4 py-3 w-32"><button class="text-blue-600 hover:underline">View</button></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 w-48 truncate">Batch B</td>
                            <td class="px-4 py-3 w-32">Planned</td>
                            <td class="px-4 py-3 w-32">13:00</td>
                            <td class="px-4 py-3 w-32">17:00</td>
                            <td class="px-4 py-3 w-32"><button class="text-blue-600 hover:underline">View</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Production Notifications -->
        <div class="mt-4">
            <h4 class="text-md font-medium mb-2">Production Notifications</h4>
            <ul class="list-disc pl-5 text-sm text-gray-700">
                <li>Batch A completed successfully.</li>
                <li>Batch B scheduled to start at 13:00.</li>
            </ul>
        </div>
    </div>

    <!-- Workforce Schedule Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Workforce Schedule</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Staff on Duty -->
            <div class="bg-yellow-50 rounded-lg p-4">
                <h4 class="font-medium mb-2">Staff on Duty</h4>
                <ul class="space-y-1 workforce-staff-list">
                    <li>Jane Doe (Baker)</li>
                    <li>John Smith (Operator)</li>
                </ul>
            </div>
            <!-- Assignments/Quick Actions -->
            <div class="bg-white rounded-lg p-4">
                <h4 class="font-medium mb-2">Quick Assign/Reassign</h4>
                <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Assign Staff</button>
            </div>
        </div>
        <!-- Workforce Notifications -->
        <div class="mt-4">
            <h4 class="text-md font-medium mb-2">Workforce Notifications</h4>
            <ul class="list-disc pl-5 text-sm text-gray-700">
                <li>John Smith assigned to Batch B.</li>
            </ul>
        </div>
    </div>

    <!-- Machine Maintenance Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Machine Maintenance</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Machine Status -->
            <div class="bg-red-50 rounded-lg p-4">
                <h4 class="font-medium mb-2">Machine Status</h4>
                <ul class="space-y-1 machine-status-list">
                    <li>Oven 1: <span class="text-green-600">Running</span></li>
                    <li>Oven 2: <span class="text-yellow-600">Maintenance</span></li>
                </ul>
            </div>
            <!-- Maintenance Alerts -->
            <div class="bg-white rounded-lg p-4">
                <h4 class="font-medium mb-2">Maintenance Alerts</h4>
                <ul class="list-disc pl-5 text-sm text-gray-700">
                    <li>Oven 2 scheduled for maintenance at 15:00.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Ingredient Needs Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Ingredient Needs</h2>
        <ul class="list-disc pl-5 text-sm text-gray-700">
            <li>Flour: Low stock (20kg left)</li>
            <li>Yeast: Sufficient</li>
        </ul>
    </div>

    <!-- Chat/Comments Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Team Chat & Comments</h2>
        <div class="mb-2">
            <input type="text" class="w-full border rounded px-3 py-2" placeholder="Type a message...">
        </div>
        <div class="h-32 overflow-y-auto bg-gray-50 rounded p-2 text-sm">
            <div><span class="font-bold">Jane:</span> Batch A is almost done!</div>
            <div><span class="font-bold">John:</span> Oven 2 needs a check.</div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- Live Production Monitoring ---
    function fetchProductionLive() {
        fetch("{{ route('bakery.bakery.production-live') }}")
            .then(res => res.json())
            .then(data => {
                document.querySelector('.production-output').textContent = data.output;
                document.querySelector('.production-target').textContent = 'Target: ' + data.target;
                // Update batch list
                const tbody = document.querySelector('.production-batch-tbody');
                tbody.innerHTML = '';
                data.batches.forEach(batch => {
                    tbody.innerHTML += `<tr>
                    <td class='px-4 py-3 w-48 truncate'>${batch.name}</td>
                    <td class='px-4 py-3 w-32'>${batch.status}</td>
                    <td class='px-4 py-3 w-32'>${batch.start}</td>
                    <td class='px-4 py-3 w-32'>${batch.end}</td>
                    <td class='px-4 py-3 w-32'><button class='text-blue-600 hover:underline'>View</button></td>
                </tr>`;
                });
                // Update trends chart
                if (window.productionTrendsChart) window.productionTrendsChart.destroy();
                const ctx = document.getElementById('productionTrendsChart').getContext('2d');
                window.productionTrendsChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'Units Produced',
                            data: data.trends,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            });
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
    }
    fetchAllLive();
    setInterval(fetchAllLive, 15000);
</script>
@endpush
@endsection