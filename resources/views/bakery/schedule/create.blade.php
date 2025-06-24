@extends('layouts.app')

@section('header')
    Create Staff Schedule
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <form method="POST" action="{{ route('bakery.schedule.store') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Employee Selection -->
                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700">Employee</label>
                        <select id="employee_id" name="employee_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            <option value="">Select an employee</option>
                            <option value="1">John Smith</option>
                            <option value="2">Sarah Johnson</option>
                            <option value="3">Mike Brown</option>
                        </select>
                        @error('employee_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Shift Date -->
                    <div>
                        <label for="shift_date" class="block text-sm font-medium text-gray-700">Shift Date</label>
                        <input type="date" name="shift_date" id="shift_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        @error('shift_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Shift Type -->
                    <div>
                        <label for="shift_type" class="block text-sm font-medium text-gray-700">Shift Type</label>
                        <select id="shift_type" name="shift_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            <option value="">Select shift type</option>
                            <option value="morning">Morning (6:00 - 14:00)</option>
                            <option value="afternoon">Afternoon (14:00 - 22:00)</option>
                            <option value="night">Night (22:00 - 6:00)</option>
                        </select>
                        @error('shift_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"></textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/90 transition-colors">
                            Create Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 