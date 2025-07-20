@extends('layouts.bakery-manager')

@section('header')
<div class="flex flex-col md:flex-row justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        <img src="/images/baguette.jpg" alt="Workforce Distribution" class="w-12 h-12 rounded-full shadow-md border-2 border-sky-400 bg-white object-cover">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Workforce Distribution Management</h1>
            <p class="mt-1 text-base text-gray-600 font-medium">Manage and monitor your workforce assignments and availability in real time.</p>
        </div>
    </div>
    <div class="text-right mt-4 md:mt-0">
        <p class="text-sm text-gray-500">Last updated</p>
        <p class="text-sm font-medium text-gray-900">{{ now()->format('M d, Y H:i') }}</p>
    </div>
</div>
@endsection

@section('content')
<div class="container mx-auto py-8 space-y-10">
    <!-- Staff Members Table -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Staff Members</h2>
            <button class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600" onclick="addStaff()">Add Staff</button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="staffTbody">
                    <!-- Populated by JS -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- Supply Centers Table -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Supply Centers</h2>
            <button class="px-4 py-2 rounded bg-green-500 text-white hover:bg-green-600" onclick="addCenter()">Add Center</button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th>Center Name</th>
                        <th>Role Needed</th>
                        <th>Shift Time</th>
                        <th>Required Staffs Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="centersTbody">
                    <!-- Populated by JS -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- Staff Assignment Calendar -->
    <div class="bg-white rounded-2xl shadow-xl p-6 mb-10">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Staff Assignment Calendar</h3>
                <p class="text-gray-600 mt-1">View and manage daily staff assignments</p>
            </div>
            <div class="flex gap-3">
                <button onclick="refreshAssignmentsForDate()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh
                </button>
                <button onclick="exportAssignmentsForDate()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export
                </button>
            </div>
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
    </div>
    <!-- Assignment Details Modal -->
    <div id="assignmentDetailsModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl relative">
            <button onclick="closeAssignmentDetailsModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
            <h3 class="text-lg font-semibold mb-4">Assignments for <span id="assignment-date-label"></span></h3>
            <div id="assignmentDetailsContent">
                <p class="text-gray-500 text-center">Loading...</p>
            </div>
        </div>
    </div>
    <!-- Shift Assignment Table -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Shift Assignments</h2>
            <button class="px-4 py-2 rounded bg-pink-500 text-white hover:bg-pink-600" onclick="autoAssign()" id="autoAssignBtn">Auto-Assign Staff</button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th>Staff Name</th>
                        <th>Role</th>
                        <th>Assigned Center</th>
                        <th>Shift Time</th>
                        <th>Assignment Status</th>
                    </tr>
                </thead>
                <tbody id="assignmentsTbody">
                    <!-- Populated by JS -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // --- Persistent Data via API ---
    let staff = [];
    let centers = [];
    let assignments = [];
    let autoAssignInProgress = false;

    // API endpoints
    const staffApi = '/api/staff';
    const centersApi = '/api/supply-centers';
    const assignmentsApi = '/api/assignments';

    // Fetch all data on page load
    async function fetchAll() {
        await Promise.all([
            fetchStaff(),
            fetchCenters(),
            fetchAssignments()
        ]);
    }

    async function fetchStaff() {
        try {
            const res = await fetch('http://127.0.0.1:8000/api/staff');
            if (!res.ok) throw new Error('Failed to fetch staff');
            staff = await res.json();
            renderStaff();
        } catch (e) {
            alert('Error loading staff: ' + e.message);
            console.error(e);
        }
    }
    async function fetchCenters() {
        try {
            const res = await fetch('http://127.0.0.1:8000/api/supply-centers');
            if (!res.ok) throw new Error('Failed to fetch centers');
            centers = await res.json();
            renderCenters();
        } catch (e) {
            alert('Error loading centers: ' + e.message);
            console.error(e);
        }
    }
    async function fetchAssignments() {
        try {
            const res = await fetch('http://127.0.0.1:8000/api/assignments');
            if (!res.ok) throw new Error('Failed to fetch assignments');
            assignments = await res.json();
            renderAssignments();
        } catch (e) {
            alert('Error loading assignments: ' + e.message);
            console.error(e);
        }
    }

    function renderStaff() {
        const tbody = document.getElementById('staffTbody');
        tbody.innerHTML = '';
        staff.forEach((s, i) => {
            tbody.innerHTML += `<tr>
            <td>${s.name}</td>
            <td>${s.role}</td>
            <td><select onchange="updateStaffStatus(${s.id}, this.value)"><option${s.status==='Present'?' selected':''}>Present</option><option${s.status==='Absent'?' selected':''}>Absent</option></select></td>
            <td>
                <button class='text-blue-600' onclick='editStaff(${s.id})'>Edit</button> |
                <button class='text-red-600' onclick='deleteStaff(${s.id})'>Delete</button>
            </td>
        </tr>`;
        });
    }

    function renderCenters() {
        const tbody = document.getElementById('centersTbody');
        tbody.innerHTML = '';
        centers.forEach((c, i) => {
            tbody.innerHTML += `<tr>
            <td>${c.name}</td>
            <td>${c.required_role}</td>
            <td>
                <select onchange='updateCenterShiftTime(${c.id}, this.value)' class='border rounded px-2 py-1 w-40'>
                    <option value='8:00AM-17:00PM' ${c.shift_time === '8:00AM-17:00PM' ? 'selected' : ''}>8:00AM-17:00PM</option>
                    <option value='6:00PM-6:00AM' ${c.shift_time === '6:00PM-6:00AM' ? 'selected' : ''}>6:00PM-6:00AM</option>
                </select>
            </td>
            <td>
                <input type='number' min='1' value='${c.required_staff_count ?? ''}' id='requiredStaffInput${c.id}' class='border rounded px-2 py-1 w-20 mr-2'>
                <button onclick='saveRequiredStaffCount(${c.id})' class='px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs'>Save</button>
            </td>
            <td>
                <button class='text-blue-600' onclick='editCenter(${c.id})'>Edit</button> |
                <button class='text-red-600' onclick='deleteCenter(${c.id})'>Delete</button>
            </td>
        </tr>`;
        });
    }

    function renderAssignments() {
        const tbody = document.getElementById('assignmentsTbody');
        tbody.innerHTML = '';
        assignments.forEach(a => {
            tbody.innerHTML += `<tr>
            <td>${a.staff_id ? (staff.find(s => s.id === a.staff_id)?.name || '-') : '-'}</td>
            <td>${a.staff_id ? (staff.find(s => s.id === a.staff_id)?.role || '-') : a.status === 'Unfilled' ? (centers.find(c => c.id === a.supply_center_id)?.required_role || '-') : '-'}</td>
            <td>${a.supply_center_id ? (centers.find(c => c.id === a.supply_center_id)?.name || '-') : '-'}</td>
            <td>${a.shift_time || '-'}</td>
            <td>${a.status}</td>
        </tr>`;
        });
    }

    async function updateStaffStatus(id, value) {
        const s = staff.find(x => x.id === id);
        if (!s) return;
        try {
            // Use the new status update endpoint
            const res = await fetch(`http://127.0.0.1:8000/api/staff/${id}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    status: value
                })
            });
            if (!res.ok) throw new Error('Failed to update staff status');

            // Refresh the staff list
            fetchStaff();

            // Trigger dashboard update
            triggerDashboardUpdate();

        } catch (e) {
            alert('Error updating staff status: ' + e.message);
            console.error(e);
        }
    }

    // Function to trigger dashboard updates
    function triggerDashboardUpdate() {
        // Try to call the dashboard update function if it exists
        if (window.parent && window.parent.fetchStaffOnDutyFromStaffTable) {
            window.parent.fetchStaffOnDutyFromStaffTable();
        } else if (typeof fetchStaffOnDutyFromStaffTable === 'function') {
            fetchStaffOnDutyFromStaffTable();
        }

        // Also try to update any other dashboard elements
        if (window.parent && window.parent.updateBakeryStats) {
            window.parent.updateBakeryStats();
        }
    }
    async function addStaff() {
        const name = prompt('Staff name?');
        if (!name) return;
        const role = prompt('Role?');
        if (!role) return;
        try {
            const res = await fetch('http://127.0.0.1:8000/api/staff', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    role,
                    status: 'Present'
                })
            });
            if (!res.ok) throw new Error('Failed to add staff');
            fetchStaff();
        } catch (e) {
            alert('Error adding staff: ' + e.message);
            console.error(e);
        }
    }
    async function editStaff(id) {
        const s = staff.find(x => x.id === id);
        if (!s) return;
        const name = prompt('Edit name:', s.name);
        if (!name) return;
        const role = prompt('Edit role:', s.role);
        if (!role) return;
        try {
            const res = await fetch(`http://127.0.0.1:8000/api/staff/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    role,
                    status: s.status
                })
            });
            if (!res.ok) throw new Error('Failed to update staff');
            fetchStaff();
        } catch (e) {
            alert('Error editing staff: ' + e.message);
            console.error(e);
        }
    }
    async function deleteStaff(id) {
        if (confirm('Delete this staff member?')) {
            try {
                const res = await fetch(`http://127.0.0.1:8000/api/staff/${id}`, {
                    method: 'DELETE'
                });
                if (!res.ok) throw new Error('Failed to delete staff');
                fetchStaff();
            } catch (e) {
                alert('Error deleting staff: ' + e.message);
                console.error(e);
            }
        }
    }
    async function addCenter() {
        const name = prompt('Center name?');
        if (!name) return;
        const required_role = prompt('Role needed?');
        if (!required_role) return;
        try {
            const res = await fetch('http://127.0.0.1:8000/api/supply-centers', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    required_role
                })
            });
            if (!res.ok) throw new Error('Failed to add center');
            fetchCenters();
        } catch (e) {
            alert('Error adding center: ' + e.message);
            console.error(e);
        }
    }
    async function updateCenterShiftTime(id, value) {
        const c = centers.find(x => x.id === id);
        if (!c) return;
        try {
            const res = await fetch(`http://127.0.0.1:8000/api/supply-centers/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: c.name,
                    required_role: c.required_role,
                    shift_time: value
                })
            });
            if (!res.ok) throw new Error('Failed to update shift time');
            fetchCenters();
        } catch (e) {
            alert('Error updating shift time: ' + e.message);
            console.error(e);
        }
    }
    async function editCenter(id) {
        const c = centers.find(x => x.id === id);
        if (!c) return;
        const name = prompt('Edit center name:', c.name);
        if (!name) return;
        const required_role = prompt('Edit role needed:', c.required_role);
        if (!required_role) return;
        let shift_time = c.shift_time || '8:00AM-17:00PM';
        shift_time = prompt('Edit shift time (8:00AM-17:00PM or 6:00PM-6:00AM):', shift_time);
        if (!["8:00AM-17:00PM", "6:00PM-6:00AM"].includes(shift_time)) shift_time = '8:00AM-17:00PM';
        try {
            const res = await fetch(`http://127.0.0.1:8000/api/supply-centers/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    required_role,
                    shift_time
                })
            });
            if (!res.ok) throw new Error('Failed to update center');
            fetchCenters();
        } catch (e) {
            alert('Error editing center: ' + e.message);
            console.error(e);
        }
    }
    async function deleteCenter(id) {
        if (confirm('Delete this center?')) {
            try {
                const res = await fetch(`http://127.0.0.1:8000/api/supply-centers/${id}`, {
                    method: 'DELETE'
                });
                if (!res.ok) throw new Error('Failed to delete center');
                fetchCenters();
            } catch (e) {
                alert('Error deleting center: ' + e.message);
                console.error(e);
            }
        }
    }
    async function autoAssign() {
        if (autoAssignInProgress) return;
        autoAssignInProgress = true;
        const btn = document.getElementById('autoAssignBtn');
        if (btn) btn.disabled = true;
        try {
            // Delete all assignments
            const current = await fetch('http://127.0.0.1:8000/api/assignments');
            if (!current.ok) throw new Error('Failed to fetch assignments');
            const allAssignments = await current.json();
            await Promise.all(allAssignments.map(a => fetch(`http://127.0.0.1:8000/api/assignments/${a.id}`, {
                method: 'DELETE'
            })));
            // Assign up to required_staff_count present staff with the same role to each center
            for (const center of centers) {
                const shiftTime = center.shift_time || '8:00AM-17:00PM';
                const requiredCount = parseInt(center.required_staff_count, 10) || 1;
                const availableStaff = staff.filter(s =>
                    s.role === center.required_role &&
                    s.status === 'Present'
                );
                // Assign up to requiredCount staff
                for (let i = 0; i < requiredCount; i++) {
                    if (availableStaff[i]) {
                        const res = await fetch('http://127.0.0.1:8000/api/assignments', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                staff_id: availableStaff[i].id,
                                supply_center_id: center.id,
                                shift_time: shiftTime,
                                status: 'Assigned',
                                assignment_date: new Date().toISOString().split('T')[0] // Today's date
                            })
                        });
                        if (!res.ok) {
                            const msg = await res.text();
                            throw new Error('Failed to assign: ' + msg);
                        }
                    } else {
                        // Not enough staff, create unfilled slot
                        const res = await fetch('http://127.0.0.1:8000/api/assignments', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                staff_id: null,
                                supply_center_id: center.id,
                                shift_time: shiftTime,
                                status: 'Unfilled',
                                assignment_date: new Date().toISOString().split('T')[0] // Today's date
                            })
                        });
                        if (!res.ok) {
                            const msg = await res.text();
                            throw new Error('Failed to assign: ' + msg);
                        }
                    }
                }
            }
            // Instantly refresh assignments table after auto-assign
            await fetchAssignments();
        } catch (e) {
            alert('Error during auto-assign: ' + e.message);
            console.error(e);
        } finally {
            autoAssignInProgress = false;
            if (btn) btn.disabled = false;
        }
    }

    async function saveRequiredStaffCount(centerId) {
        const input = document.getElementById('requiredStaffInput' + centerId);
        const value = parseInt(input.value, 10);
        if (isNaN(value) || value < 1) {
            alert('Please enter a valid number (1 or more)');
            return;
        }
        const c = centers.find(x => x.id === centerId);
        if (!c) return;
        try {
            const res = await fetch(`http://127.0.0.1:8000/api/supply-centers/${centerId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: c.name,
                    required_role: c.required_role,
                    shift_time: c.shift_time,
                    required_staff_count: value
                })
            });
            if (!res.ok) throw new Error('Failed to update required staff count');
            fetchCenters();
        } catch (e) {
            alert('Error updating required staff count: ' + e.message);
            console.error(e);
        }
    }

    // Initial render
    fetchAll();
</script>
<script>
    // Date-based Staff Assignment Calendar Functions

    // Initialize the calendar functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Set up date picker event listener
        const dateInput = document.getElementById('assignment-date');
        if (dateInput) {
            dateInput.addEventListener('change', function() {
                fetchAssignmentsForDate(this.value);
            });

            // Load initial assignments for today
            fetchAssignmentsForDate(dateInput.value);
        }
    });

    // Fetch assignments for a specific date
    function fetchAssignmentsForDate(date) {
        const assignmentList = document.getElementById('assignment-list');
        const selectedDateSpan = document.getElementById('selected-date');

        // Update the selected date display
        const dateObj = new Date(date);
        selectedDateSpan.textContent = dateObj.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // Show loading state
        assignmentList.innerHTML = '<p class="text-gray-500 text-center py-8">Loading assignments...</p>';

        // Fetch assignments for the selected date
        fetch(`http://127.0.0.1:8000/api/assignments?date=${date}`)
            .then(res => {
                if (!res.ok) {
                    throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                }
                return res.json();
            })
            .then(assignments => {
                if (!assignments || assignments.length === 0) {
                    assignmentList.innerHTML = `
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-500 text-lg font-medium">No assignments for this date</p>
                            <p class="text-gray-400 text-sm">Select a different date or create new assignments</p>
                        </div>
                    `;
                    return;
                }

                // Render assignments
                let html = '<div class="space-y-3">';
                assignments.forEach(assignment => {
                    const staffName = assignment.staff ? assignment.staff.name : 'Unassigned';
                    const centerName = assignment.supply_center ? assignment.supply_center.name : 'Unknown Center';
                    const statusColor = assignment.status === 'Assigned' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';

                    html += `
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h5 class="font-semibold text-gray-900">${staffName}</h5>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium ${statusColor}">
                                            ${assignment.status}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                        <div>
                                            <span class="font-medium">Center:</span> ${centerName}
                                        </div>
                                        <div>
                                            <span class="font-medium">Shift:</span> ${assignment.shift_time || 'Not specified'}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="editAssignment(${assignment.id})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Edit
                                    </button>
                                    <button onclick="deleteAssignment(${assignment.id})" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                assignmentList.innerHTML = html;
            })
            .catch(error => {
                console.error('Error fetching assignments:', error);
                assignmentList.innerHTML = `
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <p class="text-red-500 text-lg font-medium">Failed to load assignments</p>
                        <p class="text-gray-400 text-sm">Please try refreshing the page</p>
                    </div>
                `;
            });
    }

    // Refresh assignments for the currently selected date
    function refreshAssignmentsForDate() {
        const dateInput = document.getElementById('assignment-date');
        if (dateInput) {
            fetchAssignmentsForDate(dateInput.value);
        }
    }

    // Export assignments for the selected date
    function exportAssignmentsForDate() {
        const dateInput = document.getElementById('assignment-date');
        if (!dateInput) return;

        const date = dateInput.value;
        const dateObj = new Date(date);
        const formattedDate = dateObj.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // Fetch assignments and create CSV
        fetch(`http://127.0.0.1:8000/api/assignments?date=${date}`)
            .then(res => res.json())
            .then(assignments => {
                let csv = 'Staff Name,Role,Center,Shift Time,Status\n';
                assignments.forEach(assignment => {
                    const staffName = assignment.staff ? assignment.staff.name : 'Unassigned';
                    const staffRole = assignment.staff ? assignment.staff.role : 'N/A';
                    const centerName = assignment.supply_center ? assignment.supply_center.name : 'Unknown Center';
                    const shiftTime = assignment.shift_time || 'Not specified';
                    const status = assignment.status || 'Unknown';

                    csv += `"${staffName}","${staffRole}","${centerName}","${shiftTime}","${status}"\n`;
                });

                // Create and download the file
                const blob = new Blob([csv], {
                    type: 'text/csv'
                });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `assignments_${date}.csv`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            })
            .catch(error => {
                console.error('Error exporting assignments:', error);
                alert('Failed to export assignments. Please try again.');
            });
    }

    // Edit assignment function
    function editAssignment(assignmentId) {
        // This would open a modal to edit the assignment
        alert('Edit functionality for assignment ' + assignmentId + ' would be implemented here.');
    }

    // Delete assignment function
    function deleteAssignment(assignmentId) {
        if (confirm('Are you sure you want to delete this assignment?')) {
            fetch(`http://127.0.0.1:8000/api/assignments/${assignmentId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(res => {
                    if (res.ok) {
                        // Refresh the assignments list
                        refreshAssignmentsForDate();
                    } else {
                        throw new Error('Failed to delete assignment');
                    }
                })
                .catch(error => {
                    console.error('Error deleting assignment:', error);
                    alert('Failed to delete assignment. Please try again.');
                });
        }
    }

    // Keep the existing modal functions for backward compatibility
    function showAssignmentDetails(date) {
        document.getElementById('assignmentDetailsModal').classList.remove('hidden');
        document.getElementById('assignment-date-label').textContent = date;
        const content = document.getElementById('assignmentDetailsContent');
        content.innerHTML = '<p class="text-gray-500 text-center">Loading...</p>';
        fetch(`http://127.0.0.1:8000/api/assignments?date=${date}`)
            .then(res => res.json())
            .then(data => {
                if (!data.length) {
                    content.innerHTML = '<p class="text-gray-500 text-center">No assignments for this date.</p>';
                    return;
                }
                let html = '<table class="min-w-full text-sm"><thead><tr><th class="px-4 py-2 text-left">Staff</th><th class="px-4 py-2 text-left">Center</th><th class="px-4 py-2 text-left">Shift</th><th class="px-4 py-2 text-left">Status</th></tr></thead><tbody>';
                data.forEach(a => {
                    html += `<tr><td class='px-4 py-2'>${a.staff?.name || '-'}</td><td class='px-4 py-2'>${a.supply_center?.name || '-'}</td><td class='px-4 py-2'>${a.shift_time || '-'}</td><td class='px-4 py-2'>${a.status || '-'}</td></tr>`;
                });
                html += '</tbody></table>';
                content.innerHTML = html;
            })
            .catch(() => {
                content.innerHTML = '<p class="text-red-500 text-center">Failed to load assignments.</p>';
            });
    }

    function closeAssignmentDetailsModal() {
        document.getElementById('assignmentDetailsModal').classList.add('hidden');
    }
</script>
@endpush