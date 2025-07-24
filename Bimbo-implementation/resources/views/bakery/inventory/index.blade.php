@extends('layouts.app')

@section('content')
<div class="container mx-auto px-2 py-4 text-base" style="font-size: 1.08rem;"> <!-- Slightly increased font size -->
    <!-- Header -->
    <div class="mb-4">
        <div class="flex justify-between items-center bg-gradient-to-r from-blue-500 to-blue-300 rounded-lg p-4 shadow">
            <div>
                <h1 class="text-3xl font-extrabold text-white drop-shadow">Bakery Inventory</h1> <!-- Larger, bolder, white text -->
                <p class="text-blue-100 mt-1">Manage your bakery's inventory items and stock levels</p>
            </div>
            <a href="{{ route('bakery.inventory.create') }}"
                class="bg-gradient-to-r from-green-400 to-blue-500 hover:from-green-500 hover:to-blue-600 text-white font-bold py-2 px-5 rounded-lg shadow-lg transition-all duration-200 text-lg">
                <i class="fas fa-plus mr-2"></i>Add New Item
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div class="rounded-lg shadow-lg p-4 bg-gradient-to-br from-blue-100 to-blue-300 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-500 text-white shadow">
                    <i class="fas fa-boxes text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-base font-semibold text-blue-900">Total Items</p>
                    <p class="text-3xl font-extrabold text-blue-800">{{ $totalItems }}</p>
                </div>
            </div>
        </div>
        <div class="rounded-lg shadow-lg p-4 bg-gradient-to-br from-yellow-100 to-yellow-300 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-500 text-white shadow">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-base font-semibold text-yellow-900">Low Stock</p>
                    <p class="text-3xl font-extrabold text-yellow-800">{{ $lowStockItems }}</p>
                </div>
            </div>
        </div>
        <div class="rounded-lg shadow-lg p-4 bg-gradient-to-br from-red-100 to-red-300 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-500 text-white shadow">
                    <i class="fas fa-times-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-base font-semibold text-red-900">Out of Stock</p>
                    <p class="text-3xl font-extrabold text-red-800">{{ $outOfStockItems }}</p>
                </div>
            </div>
        </div>
        <div class="rounded-lg shadow-lg p-4 bg-gradient-to-br from-green-100 to-green-300 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-500 text-white shadow">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-base font-semibold text-green-900">Total Value</p>
                    <p class="text-3xl font-extrabold text-green-800">${{ number_format($totalValue, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg shadow mb-4 border border-blue-200">
        <div class="p-4">
            <form method="GET" action="{{ route('bakery.inventory.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-2">
                <div>
                    <label for="search" class="block text-base font-semibold text-blue-700 mb-1">Search Items</label>
                    <input type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Search by item name..."
                        class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 text-base bg-white">
                </div>
                <div>
                    <label for="type" class="block text-base font-semibold text-blue-700 mb-1">Item Type</label>
                    <select name="type" id="type" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 text-base bg-white">
                        <option value="">All Types</option>
                        @foreach($itemTypes as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-base font-semibold text-blue-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 text-base bg-white">
                        <option value="">All Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-green-400 hover:from-blue-600 hover:to-green-500 text-white font-bold py-2 px-4 rounded-md shadow-lg transition-all duration-200 text-lg">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-blue-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-blue-200">
                <thead class="bg-gradient-to-r from-blue-200 to-blue-400">
                    <tr>
                        <th class="px-5 py-3 text-left text-base font-bold text-blue-900 uppercase tracking-wider">Item</th>
                        <th class="px-5 py-3 text-left text-base font-bold text-blue-900 uppercase tracking-wider">Type</th>
                        <th class="px-5 py-3 text-left text-base font-bold text-blue-900 uppercase tracking-wider">Quantity</th>
                        <th class="px-5 py-3 text-left text-base font-bold text-blue-900 uppercase tracking-wider">Unit Price</th>
                        <th class="px-5 py-3 text-left text-base font-bold text-blue-900 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-left text-base font-bold text-blue-900 uppercase tracking-wider">Location</th>
                        <th class="px-5 py-3 text-right text-base font-bold text-blue-900 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-blue-100">
                    @forelse($inventory as $item)
                    <tr class="hover:bg-blue-50 transition-colors duration-150">
                        <td class="px-5 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-200 to-blue-400 flex items-center justify-center shadow">
                                        <i class="fas fa-box text-blue-700 text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('bakery.inventory.show', $item) }}" class="text-lg font-bold text-blue-700 hover:text-blue-900 hover:underline">
                                        {{ $item->item_name }}
                                    </a>
                                    <div class="text-base text-blue-400">{{ $item->unit }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <span class="inline-flex px-3 py-1 text-base font-bold rounded-full
                                    @if($item->item_type == 'ingredient') bg-blue-200 text-blue-900
                                    @elseif($item->item_type == 'finished_good') bg-green-200 text-green-900
                                    @elseif($item->item_type == 'packaging') bg-purple-200 text-purple-900
                                    @else bg-gray-200 text-gray-900
                                    @endif">
                                {{ ucfirst(str_replace('_', ' ', $item->item_type)) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-lg text-blue-900 font-bold">{{ $item->quantity }}</div>
                                @if($item->updated_at->diffInMinutes(now()) < 5)
                                    <div class="ml-2 w-2 h-2 bg-green-500 rounded-full animate-pulse" title="Recently updated"></div>
                                @endif
                            </div>
                            @if($item->quantity <= $item->reorder_level)
                                <div class="text-base text-red-600 font-semibold">Reorder: {{ $item->reorder_level }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-lg text-blue-900 font-bold">
                            @if($item->unit_price)
                            ${{ number_format($item->unit_price, 2) }}
                            @else
                            -
                            @endif
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            @if($item->quantity == 0)
                            <span class="inline-flex px-3 py-1 text-base font-bold rounded-full bg-red-200 text-red-900">
                                Out of Stock
                            </span>
                            @elseif($item->quantity <= $item->reorder_level)
                                <span class="inline-flex px-3 py-1 text-base font-bold rounded-full bg-yellow-200 text-yellow-900">
                                    Low Stock
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 text-base font-bold rounded-full bg-green-200 text-green-900">
                                    Available
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-base text-blue-500">
                            {{ $item->location ?: '-' }}
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-right text-lg font-bold">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('bakery.inventory.show', $item) }}"
                                    class="text-blue-600 hover:text-blue-900 bg-blue-100 rounded-full p-2 transition-colors duration-150 shadow">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($item)
                                <a href="{{ route('bakery.inventory.edit', $item) }}"
                                    class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 rounded-full p-2 transition-colors duration-150 shadow">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('bakery.inventory.destroy', $item) }}"
                                    method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 rounded-full p-2 transition-colors duration-150 shadow">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-3 text-center text-blue-400 text-lg">
                            No inventory items found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        @if($inventory->hasPages())
        <div class="bg-white px-2 py-2 border-t border-blue-200 sm:px-4">
            {{ $inventory->links() }}
        </div>
        @endif
    </div>
</div>

@if(session('success'))
<div id="success-message" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
    {{ session('success') }}
</div>
<script>
    setTimeout(function() {
        document.getElementById('success-message').style.display = 'none';
    }, 3000);
</script>
@endif
@endsection
