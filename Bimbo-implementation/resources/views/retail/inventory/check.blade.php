@extends('layouts.app')

@section('header')
    Check Inventory
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Search and Filter -->
                <div class="mb-8">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" placeholder="Search products..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div class="flex gap-4">
                            <select class="rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">All Categories</option>
                                <option value="bread">Bread</option>
                                <option value="pastries">Pastries</option>
                                <option value="cakes">Cakes</option>
                            </select>
                            <select class="rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">All Status</option>
                                <option value="in_stock">In Stock</option>
                                <option value="low_stock">Low Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Inventory Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reorder Level</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">White Bread</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Bread</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">150</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">50</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-03-20 10:30</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">In Stock</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <button class="text-primary hover:text-primary/80">Update</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Whole Wheat</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Bread</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">25</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">50</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-03-20 09:15</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Low Stock</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <button class="text-primary hover:text-primary/80">Update</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Showing 1 to 2 of 24 results
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 rounded-md border border-gray-300 text-sm text-gray-500 hover:bg-gray-50">Previous</button>
                        <button class="px-3 py-1 rounded-md border border-gray-300 text-sm text-gray-500 hover:bg-gray-50">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 