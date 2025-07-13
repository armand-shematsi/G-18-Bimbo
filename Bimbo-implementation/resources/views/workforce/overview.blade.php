@extends('layouts.bakery-manager')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Workforce Distribution Management
</h2>
@endsection

@section('content')
<!-- Distribution Overview Banner -->
<div class="bg-gradient-to-r from-purple-500 via-pink-500 to-yellow-500 rounded-lg shadow-lg mb-8">
    <div class="px-6 py-8">
        <div class="flex items-center justify-between">
            <div class="text-white">
                <h2 class="text-2xl font-bold mb-2">Distribution Overview</h2>
                <p class="text-pink-100">Monitor staff assignment, shifts, and availability across centers</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-24 h-24 text-pink-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l4 7h7l-5.5 4 2 7-5.5-4-5.5 4 2-7L1 9h7z" />
                </svg>
            </div>
        </div>
    </div>
</div>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Today's Staff Assignments</h3>
                <div class="flex items-center">
                    <input type="date" id="assignmentDate" class="border rounded px-2 py-1 mr-2" value="{{ now()->toDateString() }}">
                    <button id="autoAssignBtn" type="button" class="flex items-center p-4 bg-gradient-to-r from-pink-500 to-pink-600 rounded-lg text-white hover:from-pink-600 hover:to-pink-700 transition-all duration-200 transform hover:scale-105 focus:outline-none">
                        <div class="w-6 h-6 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span class="ml-2 font-medium">Auto-Assign Staff</span>
                    </button>
                </div>
            </div>
            <div id="assignment-message" class="mb-4 text-green-600 font-semibold hidden"></div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="assignmentsTable">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Staff</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Supply Center</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Shift</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Assignment Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Availability</th>
                        </tr>
                    </thead>
                    <tbody id="assignmentsTbody">
                        <tr>
                            <td colspan="6" class="text-center text-gray-400 py-4">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    function fetchAssignments() {
        const date = document.getElementById('assignmentDate').value;
        fetch("{{ route('bakery.workforce.assignments') }}?date=" + date)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('assignmentsTbody');
                tbody.innerHTML = '';
                if (!data.length) {
                    tbody.innerHTML = `<tr><td colspan='6' class='text-center text-gray-400 py-4'>No assignments found.</td></tr>`;
                    return;
                }
                data.forEach((a, idx) => {
                    tbody.innerHTML += `<tr>
                        <td class='px-4 py-2'>${a.name}</td>
                        <td class='px-4 py-2'>${a.role}</td>
                        <td class='px-4 py-2'>${a.assignment}</td>
                        <td class='px-4 py-2'>${a.shift}</td>
                        <td class='px-4 py-2'>${a.assignment_status.replace('_', ' ')}</td>
                        <td class='px-4 py-2 availability-cell' id='availability-${idx}'>${a.availability}</td>
                    </tr>`;
                });
            });
    }

    function autoAssignForDate() {
        const btn = document.getElementById('autoAssignBtn');
        btn.disabled = true;
        btn.textContent = 'Assigning...';
        const date = document.getElementById('assignmentDate').value;
        fetch("{{ route('bakery.workforce.auto-assign') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    date
                })
            })
            .then(res => res.json())
            .then(data => {
                const msg = document.getElementById('assignment-message');
                if (data.success) {
                    msg.textContent = data.message;
                    msg.classList.remove('hidden');
                    fetchAssignments();
                } else {
                    msg.textContent = data.message || 'Auto-assignment failed.';
                    msg.classList.remove('hidden');
                }
                setTimeout(() => msg.classList.add('hidden'), 4000);
            })
            .finally(() => {
                btn.disabled = false;
                btn.textContent = 'Auto-Assign Staff';
            });
    }
    document.getElementById('assignmentDate').addEventListener('change', function() {
        autoAssignForDate();
    });
    document.getElementById('autoAssignBtn').onclick = autoAssignForDate;
    // Optionally, trigger auto-assign on page load for the default date
    autoAssignForDate();
</script>
@endpush
@endsection