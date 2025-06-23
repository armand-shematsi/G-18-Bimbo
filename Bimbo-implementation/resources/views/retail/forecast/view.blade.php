@extends('layouts.app')

@section('header')
    View Demand Forecast
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Forecast Controls -->
                <div class="mb-8">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="daily">Daily Forecast</option>
                                <option value="weekly">Weekly Forecast</option>
                                <option value="monthly">Monthly Forecast</option>
                            </select>
                        </div>
                        <div class="flex gap-4">
                            <input type="date" class="rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            <button class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/90 transition-colors">
                                Generate Report
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Forecast Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Total Forecast</h3>
                        <p class="text-2xl font-bold text-gray-900">1,250</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">High Demand</h3>
                        <p class="text-2xl font-bold text-green-600">3</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Low Demand</h3>
                        <p class="text-2xl font-bold text-yellow-600">2</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Accuracy</h3>
                        <p class="text-2xl font-bold text-gray-900">85%</p>
                    </div>
                </div>

                <!-- Forecast Chart -->
                <div class="mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Demand Trend</h3>
                        <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                            <!-- Placeholder for chart -->
                            <p class="text-gray-500">Chart will be displayed here</p>
                        </div>
                    </div>
                </div>

                <!-- Forecast Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Demand</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Forecasted Demand</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trend</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confidence</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">White Bread</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">150/day</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">165/day</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Increasing</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">90%</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <button class="text-primary hover:text-primary/80">View Details</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Whole Wheat</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">100/day</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">95/day</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Stable</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">85%</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <button class="text-primary hover:text-primary/80">View Details</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 