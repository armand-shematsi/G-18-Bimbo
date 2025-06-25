@extends('layouts.retail-manager')

@section('header')
    Retail Manager Dashboard
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Current Inventory -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Supplier Inventory</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $supplierInventory->count() }} Items</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending Orders</dt>
                            <dd class="text-lg font-medium text-gray-900">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Items -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Low Stock Items</dt>
                            <dd class="text-lg font-medium text-red-600">{{ $lowStockItems->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Supplier Inventory Table -->
    <div class="mt-8">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Supplier Inventory</h3>
        <div class="mt-4 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reorder Level</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($supplierInventory as $item)
                        <tr class="{{ $item->needsReorder() ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->item_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->user->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->unit }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $item->status === 'available' ? 'bg-green-100 text-green-800' : 
                                       ($item->status === 'low_stock' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                                @if($item->needsReorder())
                                    <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Reorder Needed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->reorder_level }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No supplier inventory found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Actions</h3>
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <a href="{{ route('retail.orders.create') }}" class="relative block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
                <h4 class="text-lg font-medium text-gray-900">Place Order</h4>
                <p class="mt-1 text-sm text-gray-500">Order new bread products</p>
            </a>
            <a href="{{ route('retail.inventory.check') }}" class="relative block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
                <h4 class="text-lg font-medium text-gray-900">Check Inventory</h4>
                <p class="mt-1 text-sm text-gray-500">View current stock levels</p>
            </a>
            <a href="{{ route('retail.forecast.index') }}" class="relative block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
                <h4 class="text-lg font-medium text-gray-900">View Forecast</h4>
                <p class="mt-1 text-sm text-gray-500">Check demand predictions</p>
            </a>
        </div>
    </div>
@endsection 