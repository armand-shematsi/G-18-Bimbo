@extends('layouts.bakery-manager')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Staff Assignment
</h2>
@endsection

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded shadow p-6 mt-8">
    <h3 class="text-lg font-bold mb-4">Assign Staff to Supply Center</h3>
    <input type="text" id="staff-search" placeholder="Search staff by name..." class="mb-4 px-3 py-2 border rounded w-full" aria-label="Search staff by name">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="staff-table">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Current Center</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Assign To</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($staff as $user)
                <tr class="border-b staff-row">
                    <td class="px-4 py-2 staff-name">{{ $user->name }}</td>
                    <td class="px-4 py-2">
                        {{ optional($user->supplyCenter)->name ?? 'Unassigned' }}
                    </td>
                    <td class="px-4 py-2">
                        <select class="border rounded px-2 py-1 supply-center-select" data-user="{{ $user->id }}" aria-label="Assign supply center to {{ $user->name }}">
                            <option value="">Unassigned</option>
                            @foreach($supplyCenters as $center)
                            <option value="{{ $center->id }}" {{ $user->supply_center_id == $center->id ? 'selected' : '' }}>
                                {{ $center->name }}
                            </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-4 py-2">
                        <button class="bg-blue-500 text-white px-3 py-1 rounded assign-btn" data-user="{{ $user->id }}" aria-label="Assign {{ $user->name }}">Assign</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="assignment-message" class="mt-4 text-green-600 font-semibold hidden flex items-center justify-between">
        <span id="assignment-message-text"></span>
        <button id="dismiss-message" class="ml-4 text-sm text-gray-500 hover:text-gray-700">Dismiss</button>
    </div>
    <div id="assignment-error" class="mt-4 text-red-600 font-semibold hidden"></div>
</div>
@endsection

@push('scripts')
<script>
    // Staff search filter
    function filterStaffTable() {
        const search = document.getElementById('staff-search').value.toLowerCase();
        document.querySelectorAll('.staff-row').forEach(function(row) {
            const name = row.querySelector('.staff-name').textContent.toLowerCase();
            row.style.display = name.includes(search) ? '' : 'none';
        });
    }
    document.getElementById('staff-search').addEventListener('input', filterStaffTable);

    // Assignment logic
    function showMessage(msg, isError = false) {
        const messageDiv = document.getElementById('assignment-message');
        const messageText = document.getElementById('assignment-message-text');
        const errorDiv = document.getElementById('assignment-error');
        if (isError) {
            errorDiv.textContent = msg;
            errorDiv.classList.remove('hidden');
            messageDiv.classList.add('hidden');
        } else {
            messageText.textContent = msg;
            messageDiv.classList.remove('hidden');
            errorDiv.classList.add('hidden');
        }
    }
    document.getElementById('dismiss-message').onclick = function() {
        document.getElementById('assignment-message').classList.add('hidden');
    };

    document.querySelectorAll('.assign-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-user');
            const select = document.querySelector('.supply-center-select[data-user="' + userId + '"]');
            const supplyCenterId = select.value;
            const assignBtn = this;
            assignBtn.disabled = true;
            const originalText = assignBtn.textContent;
            assignBtn.textContent = 'Assigning...';
            showMessage('', false); // Hide previous messages
            document.getElementById('assignment-error').classList.add('hidden');
            fetch("{{ route('bakery.workforce.assign-staff') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        supply_center_id: supplyCenterId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showMessage(data.message);
                        // Update the Current Center cell in the same row
                        const row = assignBtn.closest('tr');
                        const currentCenterCell = row.querySelectorAll('td')[1];
                        currentCenterCell.textContent = data.supply_center_name || 'Unassigned';
                    } else {
                        showMessage('Assignment failed. Please try again.', true);
                    }
                })
                .catch(() => {
                    showMessage('Network or server error. Please try again.', true);
                })
                .finally(() => {
                    assignBtn.disabled = false;
                    assignBtn.textContent = originalText;
                });
        });
    });
</script>
@endpush