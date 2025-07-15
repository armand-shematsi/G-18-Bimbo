@extends('layouts.bakery-manager')

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
        <h3 class="text-lg font-bold text-gray-900 mb-4">Staff Assignment Calendar</h3>
        <div id="assignment-calendar"></div>
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
<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css">
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
            const res = await fetch(staffApi);
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
            const res = await fetch(centersApi);
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
            const res = await fetch(assignmentsApi);
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
                <select onchange='updateCenterShiftTime(${c.id}, this.value)' class='border rounded px-2 py-1 w-32'>
                    <option value='Day' ${c.shift_time === 'Day' ? 'selected' : ''}>Day</option>
                    <option value='Night' ${c.shift_time === 'Night' ? 'selected' : ''}>Night</option>
                </select>
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
            const res = await fetch(`${staffApi}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: s.name,
                    role: s.role,
                    status: value
                })
            });
            if (!res.ok) throw new Error('Failed to update staff status');
            fetchStaff();
        } catch (e) {
            alert('Error updating staff status: ' + e.message);
            console.error(e);
        }
    }
    async function addStaff() {
        const name = prompt('Staff name?');
        if (!name) return;
        const role = prompt('Role?');
        if (!role) return;
        try {
            const res = await fetch(staffApi, {
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
            const res = await fetch(`${staffApi}/${id}`, {
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
                const res = await fetch(`${staffApi}/${id}`, {
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
            const res = await fetch(centersApi, {
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
            const res = await fetch(`${centersApi}/${id}`, {
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
        let shift_time = c.shift_time || 'Day';
        shift_time = prompt('Edit shift time (Day/Night):', shift_time);
        if (shift_time !== 'Day' && shift_time !== 'Night') shift_time = 'Day';
        try {
            const res = await fetch(`${centersApi}/${id}`, {
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
                const res = await fetch(`${centersApi}/${id}`, {
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
            const current = await fetch(assignmentsApi);
            if (!current.ok) throw new Error('Failed to fetch assignments');
            const allAssignments = await current.json();
            await Promise.all(allAssignments.map(a => fetch(`${assignmentsApi}/${a.id}`, {
                method: 'DELETE'
            })));
            // Assign all available staff with the same role to each center, using center's shift_time
            for (const center of centers) {
                const shiftTime = center.shift_time || 'Day';
                const availableStaff = staff.filter(s =>
                    s.role === center.required_role &&
                    s.status === 'Present'
                );
                if (availableStaff.length > 0) {
                    for (const available of availableStaff) {
                        const res = await fetch(assignmentsApi, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                staff_id: available.id,
                                supply_center_id: center.id,
                                shift_time: shiftTime,
                                status: 'Assigned'
                            })
                        });
                        if (!res.ok) {
                            const msg = await res.text();
                            throw new Error('Failed to assign: ' + msg);
                        }
                    }
                } else {
                    const res = await fetch(assignmentsApi, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            staff_id: null,
                            supply_center_id: center.id,
                            shift_time: shiftTime,
                            status: 'Unfilled'
                        })
                    });
                    if (!res.ok) {
                        const msg = await res.text();
                        throw new Error('Failed to assign: ' + msg);
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

    // Initial render
    fetchAll();
</script>
<script>
    // Calendar initialization
    const calendarElem = document.getElementById('assignment-calendar');
    if (calendarElem) {
        calendarElem.innerHTML = '<input type="text" id="calendar-input" class="border rounded px-3 py-2 w-64" readonly placeholder="Select a date">';
        const input = document.getElementById('calendar-input');
        const dp = new Datepicker(input, {
            autohide: true,
            format: 'yyyy-mm-dd',
            todayHighlight: true
        });
        input.addEventListener('changeDate', function(e) {
            const date = e.target.value;
            showAssignmentDetails(date);
        });
    }

    function showAssignmentDetails(date) {
        document.getElementById('assignmentDetailsModal').classList.remove('hidden');
        document.getElementById('assignment-date-label').textContent = date;
        const content = document.getElementById('assignmentDetailsContent');
        content.innerHTML = '<p class="text-gray-500 text-center">Loading...</p>';
        fetch(`/api/assignments?date=${date}`)
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