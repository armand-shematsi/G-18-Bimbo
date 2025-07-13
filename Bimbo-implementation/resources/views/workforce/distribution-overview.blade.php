@extends('layouts.bakery-manager')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Workforce Distribution Management</h1>
        <!-- Auto-Assign Staff Button -->
        <button id="autoAssignBtn" type="button" class="flex items-center p-4 bg-gradient-to-r from-pink-500 to-pink-600 rounded-lg text-white hover:from-pink-600 hover:to-pink-700 transition-all duration-200 transform hover:scale-105 focus:outline-none">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="font-medium">Auto-Assign Staff</p>
                <p class="text-xs text-pink-100">Distribute staff to supply centers</p>
            </div>
        </button>
    </div>
    <!-- Workforce Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Staff on Duty -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Staff on Duty</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $staffOnDuty ?? '-' }}</p>
                </div>
            </div>
        </div>
        <!-- Absence -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Absence</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $absentCount ?? '-' }}</p>
                </div>
            </div>
        </div>
        <!-- Shift Filled -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Shift Filled</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $shiftFilled ?? '-' }}</p>
                </div>
            </div>
        </div>
        <!-- Overtime -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Overtime</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $overtimeCount ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Existing content for distribution overview goes here -->
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('autoAssignBtn').onclick = function() {
        const btn = this;
        btn.disabled = true;
        btn.textContent = 'Assigning...';
        fetch("{{ route('bakery.workforce.auto-assign') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    date: new Date().toISOString().slice(0, 10)
                })
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = 'Auto-Assign Staff';
                if (data.success) {
                    window.location.href = '/workforce/distribution-overview';
                } else {
                    alert('Auto-assignment failed: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = 'Auto-Assign Staff';
                alert('Auto-assignment failed due to network or server error.');
            });
    };
</script>
@endpush