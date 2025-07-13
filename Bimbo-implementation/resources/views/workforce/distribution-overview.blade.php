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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="centersTbody">
                    <!-- Populated by JS -->
                </tbody>
            </table>
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
    async function editCenter(id) {
        const c = centers.find(x => x.id === id);
        if (!c) return;
        const name = prompt('Edit center name:', c.name);
        if (!name) return;
        const required_role = prompt('Edit role needed:', c.required_role);
        if (!required_role) return;
        try {
            const res = await fetch(`${centersApi}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    required_role,
                    location: c.location
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
            // Assign
            for (const center of centers) {
                const available = staff.find(s => s.role === center.required_role && s.status === 'Present');
                let res;
                if (available) {
                    res = await fetch(assignmentsApi, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            staff_id: available.id,
                            supply_center_id: center.id,
                            shift_time: '08:00-16:00',
                            status: 'Assigned'
                        })
                    });
                } else {
                    res = await fetch(assignmentsApi, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            staff_id: null,
                            supply_center_id: center.id,
                            shift_time: '08:00-16:00',
                            status: 'Unfilled'
                        })
                    });
                }
                if (!res.ok) {
                    const msg = await res.text();
                    throw new Error('Failed to assign: ' + msg);
                }
            }
            fetchAssignments();
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
@endpush