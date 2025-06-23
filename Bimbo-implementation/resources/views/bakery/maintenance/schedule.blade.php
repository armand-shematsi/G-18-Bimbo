@extends('layouts.bakery-manager')

@section('header')
    Schedule Maintenance
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <form method="POST" action="{{ route('bakery.maintenance.store') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Machine Selection -->
                    <div>
                        <label for="machine_id" class="block text-sm font-medium text-gray-700">Machine</label>
                        <select id="machine_id" name="machine_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            <option value="">Select a machine</option>
                            <option value="1">Mixer A-1</option>
                            <option value="2">Oven B-2</option>
                            <option value="3">Packaging Line C-1</option>
                        </select>
                        @error('machine_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Maintenance Date -->
                    <div>
                        <label for="maintenance_date" class="block text-sm font-medium text-gray-700">Maintenance Date</label>
                        <input type="date" name="maintenance_date" id="maintenance_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        @error('maintenance_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Maintenance Type -->
                    <div>
                        <label for="maintenance_type" class="block text-sm font-medium text-gray-700">Maintenance Type</label>
                        <select id="maintenance_type" name="maintenance_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            <option value="">Select maintenance type</option>
                            <option value="routine">Routine Check</option>
                            <option value="repair">Repair</option>
                            <option value="emergency">Emergency</option>
                        </select>
                        @error('maintenance_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Technician -->
                    <div>
                        <label for="technician_id" class="block text-sm font-medium text-gray-700">Technician</label>
                        <select id="technician_id" name="technician_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            <option value="">Select a technician</option>
                            <option value="1">John Smith</option>
                            <option value="2">Sarah Johnson</option>
                            <option value="3">Mike Brown</option>
                        </select>
                        @error('technician_id')
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
                            Schedule Maintenance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 