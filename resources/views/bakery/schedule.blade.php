@extends('layouts.app')

@section('header')
    Workforce Schedule
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Schedule Controls -->
                <div class="mb-6 flex justify-between items-center">
                    <div class="flex space-x-4">
                        <button class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/90 transition-colors">
                            Add Shift
                        </button>
                        <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50 transition-colors">
                            Export Schedule
                        </button>
                    </div>
                    <div class="flex items-center space-x-4">
                        <select class="rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <option>This Week</option>
                            <option>Next Week</option>
                            <option>This Month</option>
                        </select>
                    </div>
                </div>

                <!-- Schedule Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monday</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tuesday</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wednesday</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thursday</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Friday</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saturday</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sunday</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">John Smith</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">6:00 - 14:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">6:00 - 14:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">6:00 - 14:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">6:00 - 14:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">6:00 - 14:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Off</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Off</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Sarah Johnson</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">14:00 - 22:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">14:00 - 22:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">14:00 - 22:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">14:00 - 22:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">14:00 - 22:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Off</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Off</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Mike Brown</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">22:00 - 6:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">22:00 - 6:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">22:00 - 6:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">22:00 - 6:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">22:00 - 6:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Off</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Off</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Staff Availability -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Staff Availability</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Available Today</h4>
                            <p class="text-2xl font-bold text-primary">12</p>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">On Leave</h4>
                            <p class="text-2xl font-bold text-yellow-600">2</p>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Total Staff</h4>
                            <p class="text-2xl font-bold text-gray-900">15</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 