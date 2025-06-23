@extends('layouts.bakery-manager')

@section('header')
    Production Monitoring
@endsection

@section('content')
<div class="mb-6 flex space-x-4">
    <a href="{{ route('bakery.production.start') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Start Production</a>
    <a href="{{ route('bakery.batches.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">Production Batches</a>
    <a href="{{ route('bakery.schedule') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">Production Schedule</a>
</div>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Production Overview Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Production</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Total Loaves</p>
                                <p class="text-2xl font-bold text-primary">1,250</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Target</p>
                                <p class="text-2xl font-bold text-gray-900">1,500</p>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 83%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Quality Metrics Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quality Metrics</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Quality Score</p>
                                <p class="text-2xl font-bold text-green-600">98%</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Rejection Rate</p>
                                <p class="text-2xl font-bold text-red-600">2%</p>
                            </div>
                        </div>
                    </div>

                    <!-- Efficiency Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Efficiency</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Production Rate</p>
                                <p class="text-2xl font-bold text-primary">125 loaves/hr</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Downtime</p>
                                <p class="text-2xl font-bold text-gray-900">15 min</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Production Line Status -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Production Line Status</h3>
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Line</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Output</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Efficiency</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Line 1</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Running</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">White Bread</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">450 loaves</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">95%</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Line 2</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Maintenance</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Whole Wheat</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">0 loaves</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Line 3</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Running</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sourdough</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">380 loaves</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">92%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 