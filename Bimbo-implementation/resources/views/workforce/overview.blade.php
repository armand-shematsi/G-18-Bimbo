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
        <!-- Removed Today's Staff Assignments table and related controls -->
    </div>
</div>
@push('scripts')
<script>
    // The fetchAssignments and autoAssignForDate functions are no longer needed
    // as the table and related controls have been removed.
    // Keeping the script block structure as it was in the original file.
</script>
@endpush
@endsection