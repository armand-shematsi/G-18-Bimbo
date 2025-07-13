@push('scripts')
<script>
    // --- Real-time AJAX Data ---
    let staff = [];
    let centers = [];
    let assignments = [];

    async function fetchStaff() {
        const res = await fetch('/api/staff');
        staff = await res.json();
        renderStaff();
    }
    async function fetchCenters() {
        const res = await fetch('/api/supply-centers');
        centers = await res.json();
        renderCenters();
    }
    async function fetchAssignments() {
        const res = await fetch('/api/assignments');
        assignments = await res.json();
        renderAssignments();
    }
    async function addStaff() {
        const name = prompt('Staff name?');
        if (!name) return;
        const role = prompt('Role?');
        if (!role) return;
        await fetch('/api/staff', {
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
        fetchStaff();
    }
    async function editStaff(i) {
        const s = staff[i];
        const name = prompt('Edit name:', s.name);
        if (!name) return;
        const role = prompt('Edit role:', s.role);
        if (!role) return;
        await fetch(`/api/staff/${s.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name,
                role
            })
        });
        fetchStaff();
    }
    async function deleteStaff(i) {
        if (confirm('Delete this staff member?')) {
            await fetch(`/api/staff/${staff[i].id}`, {
                method: 'DELETE'
            });
            fetchStaff();
        }
    }
    async function updateStaffStatus(i, value) {
        await fetch(`/api/staff/${staff[i].id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: value
            })
        });
        fetchStaff();
    }
    async function addCenter() {
        const name = prompt('Center name?');
        if (!name) return;
        const role_needed = prompt('Role needed?');
        if (!role_needed) return;
        await fetch('/api/supply-centers', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name,
                required_role: role_needed
            })
        });
        fetchCenters();
    }
    async function editCenter(i) {
        const c = centers[i];
        const name = prompt('Edit center name:', c.name);
        if (!name) return;
        const role_needed = prompt('Edit role needed:', c.required_role);
        if (!role_needed) return;
        await fetch(`/api/supply-centers/${c.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name,
                required_role: role_needed
            })
        });
        fetchCenters();
    }
    async function deleteCenter(i) {
        if (confirm('Delete this center?')) {
            await fetch(`/api/supply-centers/${centers[i].id}`, {
                method: 'DELETE'
            });
            fetchCenters();
        }
    }
    async function autoAssign() {
        await fetch('/api/assignments/auto-assign', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                shift_time: '08:00-16:00'
            })
        });
        fetchAssignments();
    }

    function renderStaff() {
        const tbody = document.getElementById('staffTbody');
        tbody.innerHTML = '';
        staff.forEach((s, i) => {
            tbody.innerHTML += `<tr>
            <td>${s.name}</td>
            <td>${s.role}</td>
            <td><select onchange="updateStaffStatus(${i}, this.value)"><option${s.status==='Present'?' selected':''}>Present</option><option${s.status==='Absent'?' selected':''}>Absent</option></select></td>
            <td>
                <button class='text-blue-600' onclick='editStaff(${i})'>Edit</button> |
                <button class='text-red-600' onclick='deleteStaff(${i})'>Delete</button>
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
                <button class='text-blue-600' onclick='editCenter(${i})'>Edit</button> |
                <button class='text-red-600' onclick='deleteCenter(${i})'>Delete</button>
            </td>
        </tr>`;
        });
    }

    function renderAssignments() {
        const tbody = document.getElementById('assignmentsTbody');
        tbody.innerHTML = '';
        assignments.forEach(a => {
            tbody.innerHTML += `<tr>
            <td>${a.staff ? a.staff.name : '-'}</td>
            <td>${a.staff ? a.staff.role : a.status === 'Unfilled' ? a.supply_center.required_role : '-'}</td>
            <td>${a.supply_center ? a.supply_center.name : '-'}</td>
            <td>${a.shift_time}</td>
            <td>${a.status}</td>
        </tr>`;
        });
    }
    // Initial load
    fetchStaff();
    fetchCenters();
    fetchAssignments();
</script>
@endpush