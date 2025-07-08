@extends('layouts.bakery-manager')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Shift Scheduling
</h2>
@endsection

@section('content')
<div class="max-w-5xl mx-auto bg-white rounded shadow p-6 mt-8">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold">Scheduled Shifts</h3>
        <button id="openShiftModal" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400" aria-label="Schedule a new shift">+ Schedule Shift</button>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="shiftsTable">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Staff</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Supply Center</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Start Time</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">End Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shifts as $shift)
                <tr>
                    <td class="px-4 py-2">{{ $shift->user->name ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $shift->supplyCenter->name ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $shift->start_time }}</td>
                    <td class="px-4 py-2">{{ $shift->end_time }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="shift-message" class="mt-4 text-green-600 font-semibold hidden flex items-center justify-between">
        <span id="shift-message-text"></span>
        <button id="dismiss-shift-message" class="ml-4 text-sm text-gray-500 hover:text-gray-700">Dismiss</button>
    </div>
    <div id="shift-error" class="mt-4 text-red-600 font-semibold hidden"></div>
</div>

<!-- Shift Modal -->
<div id="shiftModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Schedule New Shift</h3>
        <form id="shiftForm" method="POST" action="{{ route('bakery.workforce.shifts.store') }}">
            @csrf
            <div class="mb-2">
                <label class="block text-sm font-medium">Staff</label>
                <select name="user_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Staff</option>
                    @foreach($staff as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium">Supply Center</label>
                <select name="supply_center_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Center</option>
                    @foreach($supplyCenters as $center)
                    <option value="{{ $center->id }}">{{ $center->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium">Start Time</label>
                <div class="flex space-x-2">
                    <input type="time" name="start_time_raw" id="start_time_raw" class="w-full border rounded px-3 py-2" required>
                    <select name="start_time_ampm" id="start_time_ampm" class="border rounded px-2 py-2" required>
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                    </select>
                </div>
                <input type="hidden" name="start_time" id="start_time">
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium">End Date</label>
                <input type="date" name="end_date" id="end_date" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium">End Time</label>
                <div class="flex space-x-2">
                    <input type="time" name="end_time_raw" id="end_time_raw" class="w-full border rounded px-3 py-2" required>
                    <select name="end_time_ampm" id="end_time_ampm" class="border rounded px-2 py-2" required>
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                    </select>
                </div>
                <input type="hidden" name="end_time" id="end_time">
            </div>
            <div class="flex justify-end">
                <button type="button" id="closeShiftModal" class="mr-2 px-4 py-2 rounded bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white">Schedule</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal logic
        const shiftModal = document.getElementById('shiftModal');
        const openBtn = document.getElementById('openShiftModal');
        const closeBtn = document.getElementById('closeShiftModal');
        const shiftForm = document.getElementById('shiftForm');
        const scheduleBtn = shiftForm.querySelector('button[type="submit"]');
        const shiftMessage = document.getElementById('shift-message');
        const shiftMessageText = document.getElementById('shift-message-text');
        const dismissShiftMessage = document.getElementById('dismiss-shift-message');
        const shiftError = document.getElementById('shift-error');

        openBtn.onclick = function() {
            shiftModal.classList.remove('hidden');
            shiftError.classList.add('hidden');
            shiftForm.reset();
            scheduleBtn.disabled = false;
            scheduleBtn.textContent = 'Schedule';
            shiftForm.querySelector('select[name="user_id"]').focus();
        };
        closeBtn.onclick = function() {
            shiftModal.classList.add('hidden');
        };
        if (dismissShiftMessage) {
            dismissShiftMessage.onclick = function() {
                shiftMessage.classList.add('hidden');
            };
        }

        // AJAX form submission
        shiftForm.onsubmit = function(e) {
            e.preventDefault();
            shiftError.classList.add('hidden');
            scheduleBtn.disabled = true;
            const originalText = scheduleBtn.textContent;
            scheduleBtn.textContent = 'Scheduling...';
            const data = Object.fromEntries(new FormData(shiftForm).entries());
            fetch("{{ route('bakery.workforce.shifts.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Add new shift to table
                        const tbody = document.querySelector('#shiftsTable tbody');
                        const row = document.createElement('tr');
                        row.innerHTML = `<td class='px-4 py-2'>${data.shift.staff}</td><td class='px-4 py-2'>${data.shift.center}</td><td class='px-4 py-2'>${data.shift.start_time}</td><td class='px-4 py-2'>${data.shift.end_time}</td>`;
                        tbody.prepend(row);
                        shiftMessageText.textContent = data.message;
                        shiftMessage.classList.remove('hidden');
                        setTimeout(() => shiftMessage.classList.add('hidden'), 4000);
                        shiftModal.classList.add('hidden');
                        shiftForm.reset();
                    } else {
                        shiftError.textContent = data.message || 'Scheduling failed. Please check your input.';
                        shiftError.classList.remove('hidden');
                    }
                })
                .catch(() => {
                    shiftError.textContent = 'Network or server error. Please try again.';
                    shiftError.classList.remove('hidden');
                })
                .finally(() => {
                    scheduleBtn.disabled = false;
                    scheduleBtn.textContent = originalText;
                });
        };

        document.getElementById('shiftForm').addEventListener('submit', function(e) {
            function to24(time, ampm) {
                let [h, m] = time.split(':');
                h = parseInt(h);
                if (ampm === 'PM' && h < 12) h += 12;
                if (ampm === 'AM' && h === 12) h = 0;
                return (h < 10 ? '0' : '') + h + ':' + m;
            }
            const sd = document.getElementById('start_date').value;
            const st = document.getElementById('start_time_raw').value;
            const stam = document.getElementById('start_time_ampm').value;
            document.getElementById('start_time').value = sd + 'T' + to24(st, stam);
            const ed = document.getElementById('end_date').value;
            const et = document.getElementById('end_time_raw').value;
            const etam = document.getElementById('end_time_ampm').value;
            document.getElementById('end_time').value = ed + 'T' + to24(et, etam);
        });

        // --- AM/PM Dropdown Sync ---
        function updateTimeInput(timeInput, ampmSelect) {
            ampmSelect.addEventListener('change', function() {
                let [h, m] = timeInput.value.split(':');
                if (!h || !m) return;
                h = parseInt(h);
                if (this.value === 'PM' && h < 12) h += 12;
                if (this.value === 'AM' && h === 12) h = 0;
                h = (h < 10 ? '0' : '') + h;
                timeInput.value = h + ':' + m;
            });
        }
        const startTimeInput = document.getElementById('start_time_raw');
        const startTimeAMPM = document.getElementById('start_time_ampm');
        const endTimeInput = document.getElementById('end_time_raw');
        const endTimeAMPM = document.getElementById('end_time_ampm');
        if (startTimeInput && startTimeAMPM) updateTimeInput(startTimeInput, startTimeAMPM);
        if (endTimeInput && endTimeAMPM) updateTimeInput(endTimeInput, endTimeAMPM);
    });
</script>
@endpush