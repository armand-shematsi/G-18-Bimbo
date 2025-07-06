@extends('layouts.bakery-manager')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Bakery Manager Dashboard</h1>
        <p class="mt-1 text-sm text-gray-600">Welcome back, {{ auth()->user()->name ?? 'Bakery Manager' }}! Here's your bakery overview.</p>
    </div>
    <div class="text-right">
        <p class="text-sm text-gray-500 mt-2">Last updated</p>
        <p class="text-sm font-medium text-gray-900">{{ now()->format('M d, Y H:i') }}</p>
    </div>
</div>
@endsection

@section('navigation-links')
<a href="{{ route('bakery.production') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
    Production Monitoring
</a>
<a href="{{ route('bakery.maintenance') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
    Machine Maintenance
</a>
<a href="{{ route('bakery.order-processing') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
    Order Processing
</a>
@endsection

@section('content')
<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-sky-400 via-sky-500 to-sky-600 rounded-lg shadow-lg mb-8">
    <div class="px-6 py-8">
        <div class="flex items-center justify-between">
            <div class="text-white">
                @php
                $hour = now()->hour;
                if ($hour >= 5 && $hour < 12) {
                    $greeting='Good Morning' ;
                    } elseif ($hour>= 12 && $hour < 17) {
                        $greeting='Good Afternoon' ;
                        } else {
                        $greeting='Good Evening' ;
                        }
                        @endphp
                        <h2 class="text-2xl font-bold mb-2">{{ $greeting }}, {{ auth()->user()->name ?? 'Bakery Manager' }}!</h2>
                        <p class="text-sky-100">Monitor production, manage workforce, and keep your bakery running smoothly</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-24 h-24 text-sky-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Today's Output -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Today's Output</p>
                <p class="text-2xl font-bold text-gray-900 production-output">{{ $todaysOutput ?? '-' }}</p>
                <p class="text-xs text-gray-500">Loaves produced</p>
            </div>
        </div>
    </div>
    <!-- Production Target -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Production Target</p>
                <p class="text-2xl font-bold text-gray-900 production-target">{{ $productionTarget ?? '-' }}</p>
                <p class="text-xs text-gray-500">Target for today</p>
            </div>
        </div>
    </div>
    <!-- Active Staff -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Staff</p>
                <p class="text-2xl font-bold text-gray-900 active-staff">{{ $activeStaffCount ?? '-' }}</p>
                <p class="text-xs text-gray-500">On duty now</p>
            </div>
        </div>
    </div>
    <!-- Machines Running -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Machines Running</p>
                <p class="text-2xl font-bold text-gray-900 machines-running">-</p>
                <p class="text-xs text-gray-500">Ovens/mixers active</p>
            </div>
        </div>
    </div>
</div>

<!-- Success Message -->
<div id="shift-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 hidden">
    <span id="shift-message-text"></span>
    <button onclick="document.getElementById('shift-message').classList.add('hidden')" class="float-right text-sm text-green-500 hover:text-green-700">&times;</button>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Quick Actions & Alerts (1/3) -->
    <div class="lg:col-span-1 space-y-8">
        <div class="bg-white rounded-xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-6 space-y-4">
                <a href="{{ route('bakery.production') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white w-full mb-2 hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium">Start New Production</p>
                        <p class="text-xs text-blue-100">Start Batch</p>
                    </div>
                </a>
                <a href="{{ route('bakery.workforce.shifts') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white w-full mb-2 hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium">Shift Scheduling</p>
                        <p class="text-xs text-blue-100">Plan staff shifts</p>
                    </div>
                </a>
                <a href="{{ route('bakery.maintenance') }}" class="flex items-center p-4 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg text-white w-full mb-2 hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200 transform hover:scale-105">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium">Maintain Machines</p>
                        <p class="text-xs text-yellow-100">Log Maintenance</p>
                    </div>
                </a>
                <a href="{{ route('bakery.order-processing') }}" class="flex items-center p-4 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg text-white w-full mb-2 hover:from-indigo-600 hover:to-indigo-700 transition-all duration-200 transform hover:scale-105">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium">Order Processing</p>
                        <p class="text-xs text-indigo-100">Place/Receive Orders</p>
                    </div>
                </a>
                <button onclick="document.getElementById('assignShiftModal').style.display='flex'" class="flex items-center p-4 bg-gradient-to-r from-green-500 to-green-600 rounded-lg text-white w-full mb-2 hover:from-green-600 hover:to-green-700 transition-all duration-200 transform hover:scale-105">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium">Assign Shift</p>
                        <p class="text-xs text-green-100">Schedule Staff</p>
                    </div>
                </button>
            </div>
        </div>
        <!-- Ingredient Alerts and Machine Alerts removed; assign to their respective dashboards -->
    </div>
</div>

<!-- Modern Action Cards Row -->
<!-- Modern Action Cards Row -->

<!-- Activity Timeline -->
<div class="mt-8 bg-white rounded-xl shadow-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
    </div>
    <div class="p-6">
        <div class="flow-root">
            <ul class="-mb-8 activity-timeline">
                <li class="relative pb-8">
                    <div class="relative flex space-x-3">
                        <div>
                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                            <div>
                                <p class="text-sm text-gray-500">Dashboard accessed</p>
                            </div>
                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                <time>{{ now()->format('M d, H:i') }}</time>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Assign Task Modal -->
<div id="assignTaskModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Assign Task</h3>
        <form id="assignTaskForm">
            <div class="mb-2">
                <label class="block text-sm font-medium">Title</label>
                <input type="text" name="title" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" class="w-full border rounded px-3 py-2"></textarea>
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium">Worker</label>
                <select name="user_id" class="w-full border rounded px-3 py-2" required></select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Shift</label>
                <select name="shift_id" class="w-full border rounded px-3 py-2"></select>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeAssignTaskModal()" class="mr-2 px-4 py-2 rounded bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white">Assign</button>
            </div>
        </form>
    </div>
</div>

<!-- Batch Modal -->
<div id="batchModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4" id="batchModalTitle">New Batch</h3>
        <form id="batchForm">
            <input type="hidden" name="batch_id" id="batch_id">
            <div class="mb-2">
                <label class="block text-sm font-medium">Name</label>
                <input type="text" name="name" id="batch_name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium">Status</label>
                <select name="status" id="batch_status" class="w-full border rounded px-3 py-2" required>
                    <option value="planned">Planned</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium">Scheduled Start</label>
                <input type="datetime-local" name="scheduled_start" id="batch_scheduled_start" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium">Actual Start</label>
                <input type="datetime-local" name="actual_start" id="batch_actual_start" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium">Actual End</label>
                <input type="datetime-local" name="actual_end" id="batch_actual_end" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Notes</label>
                <textarea name="notes" id="batch_notes" class="w-full border rounded px-3 py-2"></textarea>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeBatchModal()" class="mr-2 px-4 py-2 rounded bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Assign Shift Modal -->
<div id="assignShiftModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center;" onclick="if(event.target===this)this.style.display='none'">
    <div style="background:#fff; padding:2rem; border-radius:8px; max-width:400px; margin:auto; position:relative;">
        <button onclick="document.getElementById('assignShiftModal').style.display='none'" style="position:absolute; top:8px; right:12px;">&times;</button>
        <h2 class="text-lg font-bold mb-4">Assign Shift</h2>
        <form id="assignShiftForm" data-staff-centers="{{ json_encode($staff->pluck('supply_center_id', 'id')) }}">
            <label>Staff:</label>
            <select name="user_id" class="w-full mb-4 border rounded p-2" required>
                <option value="">Select Staff</option>
                @foreach($staff as $staffMember)
                <option value="{{ $staffMember->id }}">{{ $staffMember->name }}</option>
                @endforeach
            </select>
            <label>Supply Center:</label>
            <select name="supply_center_id" class="w-full mb-4 border rounded p-2" required>
                <option value="">Select Center</option>
                @foreach($supplyCenters as $center)
                <option value="{{ $center->id }}">{{ $center->name }}</option>
                @endforeach
            </select>
            <label>Start Time:</label>
            <input type="datetime-local" name="start_time" class="w-full mb-4 border rounded p-2" required>
            <label>End Time:</label>
            <input type="datetime-local" name="end_time" class="w-full mb-4 border rounded p-2" required>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Assign</button>
        </form>
    </div>
</div>

<!-- Start Batch Modal -->
<div id="startBatchModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:2rem; border-radius:8px; max-width:400px; margin:auto; position:relative;">
        <button onclick="document.getElementById('startBatchModal').style.display='none'" style="position:absolute; top:8px; right:12px;">&times;</button>
        <h2 class="text-lg font-bold mb-4">Start New Batch</h2>
        <form id="startBatchForm">
            <label>Batch Name:</label>
            <select name="name" class="w-full mb-4 border rounded p-2" required>
                @foreach(\App\Models\Product::all() as $product)
                <option value="{{ $product->name }}">{{ $product->name }}</option>
                @endforeach
            </select>
            <label>Production Line:</label>
            <select name="production_line_id" class="w-full mb-4 border rounded p-2" required>
                @foreach(\App\Models\ProductionLine::all() as $line)
                <option value="{{ $line->id }}">{{ $line->name }}</option>
                @endforeach
            </select>
            <label>Scheduled Start:</label>
            <input type="datetime-local" name="scheduled_start" class="w-full mb-4 border rounded p-2" required>
            <label>Notes:</label>
            <textarea name="notes" class="w-full mb-4 border rounded p-2"></textarea>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Start Batch</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/bakery-manager.js') }}"></script>
@endpush