@extends('layouts.supplier')

@section('header')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Inventory Management</h1>
        <p class="text-sm text-gray-500">Current stock levels and movement tracking</p>
    </div>
</div>
@endsection

@section('navigation-links')
<a href="{{ route('supplier.inventory.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:text-gray-700 focus:border-gray-300">
    Current Inventory
</a>
<a href="{{ route('supplier.stockin.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
    Stock In
</a>
<a href="{{ route('supplier.stockout.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
    Stock Out
</a>
@endsection

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #e0f2fe 0%, #f1f5f9 100%);
    }
    .glass-card {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 100%);
        border-radius: 1.5rem;
        box-shadow: 0 4px 24px 0 rgba(59, 130, 246, 0.10);
        border: 1.5px solid #bae6fd;
        padding: 2rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: box-shadow 0.2s, transform 0.2s;
        position: relative;
        overflow: hidden;
    }
    .glass-card:hover {
        box-shadow: 0 12px 36px 0 rgba(59, 130, 246, 0.18);
        transform: translateY(-2px) scale(1.03);
    }
    .icon-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        padding: 0.9rem;
        background: linear-gradient(135deg, #38bdf8 0%, #6366f1 100%);
        color: #fff;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.10);
    }
    .inventory-table {
        border-radius: 1.25rem;
        overflow: hidden;
        border: 1.5px solid #bae6fd;
        box-shadow: 0 2px 12px 0 rgba(59,130,246,0.08);
        background: #fff;
    }
    .inventory-table th {
        position: sticky;
        top: 0;
        background: linear-gradient(90deg, #e0e7ff 0%, #f0f9ff 100%);
        z-index: 1;
        font-weight: 800;
        color: #3730a3;
        box-shadow: 0 2px 8px rgba(59,130,246,0.04);
        padding-top: 1rem;
        padding-bottom: 1rem;
        font-size: 0.95rem;
        letter-spacing: 0.05em;
    }
    .inventory-table td {
        padding-top: 1rem;
        padding-bottom: 1rem;
        font-size: 1rem;
        font-weight: 500;
        transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
    }
    .inventory-table tbody tr:nth-child(even) {
        background: #f8fafc;
    }
    .inventory-table tbody tr:nth-child(odd) {
        background: #fff;
    }
    .inventory-table tr:hover {
        background: #e0e7ff !important;
        box-shadow: 0 4px 16px 0 rgba(99,102,241,0.10);
        transform: scale(1.01);
    }
    .add-btn {
        background: linear-gradient(90deg, #38bdf8 0%, #6366f1 100%);
        color: #fff;
        font-weight: bold;
        border-radius: 0.75rem;
        box-shadow: 0 2px 8px rgba(59,130,246,0.10);
        transition: background 0.2s, transform 0.2s;
    }
    .add-btn:hover {
        background: linear-gradient(90deg, #6366f1 0%, #38bdf8 100%);
        transform: scale(1.04);
    }
    #quantityModal .relative {
        border-radius: 1.25rem;
        box-shadow: 0 8px 32px 0 rgba(59,130,246,0.18);
        border: 1.5px solid #bae6fd;
    }
</style>
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="glass-card">
        <div class="icon-badge bg-blue-400">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Total Items</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $totalItems }}</p>
        </div>
    </div>
    <div class="glass-card">
        <div class="icon-badge bg-green-400">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Total Value</p>
            <p class="text-2xl font-semibold text-gray-900">${{ number_format($totalValue, 2) }}</p>
        </div>
    </div>
    <div class="glass-card">
        <div class="icon-badge bg-yellow-400">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Low Stock</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $lowStockItems }}</p>
        </div>
    </div>
    <div class="glass-card">
        <div class="icon-badge bg-red-400">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Out of Stock</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $outOfStockItems }}</p>
        </div>
    </div>
</div>
<!-- Add New Item Button -->
@if($inventory->count() > 0)
<div class="flex justify-end mb-4">
    <a href="{{ route('supplier.inventory.create') }}" class="add-btn inline-flex items-center px-6 py-2 text-base rounded-xl shadow font-semibold uppercase tracking-wide">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Add New Item
    </a>
</div>
@endif
<!-- Inventory Table -->
<div class="bg-white rounded-2xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-900">Current Inventory Levels</h3>
        <p class="text-sm text-gray-500">Stock levels are automatically updated when items are added or removed</p>
    </div>
    @if($inventory->count() > 0)
        <div class="overflow-x-auto">
            <table class="inventory-table min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Current Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Unit Price</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Total Value</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Last Updated</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($inventory as $item)
                    <tr class="hover:bg-blue-50 transition-shadow">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <a href="{{ route('supplier.inventory.show', $item->id) }}" class="text-base font-bold text-blue-600 hover:text-blue-900 cursor-pointer">
                                    {{ $item->item_name }}
                                </a>
                                <div class="text-xs text-gray-500">{{ $item->item_type ?? 'N/A' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-base font-semibold text-gray-900">{{ $item->quantity }}</span>
                                <span class="text-xs text-gray-500 ml-1">{{ $item->unit }}</span>
                            </div>
                            @if($item->reorder_level > 0)
                                <div class="text-xs text-gray-400">Reorder: {{ $item->reorder_level }} {{ $item->unit }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                {{ $item->status === 'available' ? 'bg-green-100 text-green-800' :
                                   ($item->status === 'low_stock' ? 'bg-yellow-100 text-yellow-800' :
                                   'bg-red-100 text-red-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-base text-gray-900">
                            ${{ number_format($item->unit_price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-900">
                            ${{ number_format($item->quantity * $item->unit_price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                            {{ $item->updated_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-base font-medium">
                            <div class="flex space-x-2 justify-end">
                                <a href="{{ route('supplier.inventory.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 font-semibold">Edit</a>
                                <button onclick="updateQuantity({{ $item->id }}, '{{ $item->item_name }}', {{ $item->quantity }})" class="text-green-600 hover:text-green-900 font-semibold">Update Qty</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <h3 class="mt-2 text-base font-bold text-gray-900">No inventory items</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by adding your first inventory item.</p>
            <div class="mt-6">
                <a href="{{ route('supplier.inventory.create') }}" class="add-btn inline-flex items-center px-6 py-2 text-base rounded-xl shadow font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Inventory Item
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Quick Update Modal -->
<div id="quantityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Quantity</h3>
            <form id="quantityForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Item</label>
                    <p id="itemName" class="text-sm text-gray-900"></p>
                </div>
                <div class="mb-4">
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">New Quantity</label>
                    <input type="number" id="quantity" name="quantity" min="0" step="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateQuantity(itemId, itemName, currentQuantity) {
    document.getElementById('itemName').textContent = itemName;
    document.getElementById('quantity').value = currentQuantity;
    document.getElementById('quantityForm').action = `/supplier/inventory/${itemId}/update-quantity`;
    document.getElementById('quantityModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('quantityModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('quantityModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection
