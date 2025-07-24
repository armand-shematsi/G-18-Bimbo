
@extends('layouts.retail-manager')

{{-- Loading Spinner --}}
<div id="pageLoader" class="fixed inset-0 z-50 flex items-center justify-center bg-white bg-opacity-80">
    <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-600 border-b-4 border-gray-200"></div>
</div>

@section('header')
<div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 transition-colors duration-300">Inventory Management</h1>
        <p class="text-sm text-gray-500 dark:text-gray-300 transition-colors duration-300">Current stock levels and movement tracking</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('retail.inventory.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New Item
        </a>
    </div>
</div>
@endsection

@section('content')
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6 flex items-center">
        <div class="p-2 bg-blue-100 rounded-lg">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Total Items</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $items->count() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 flex items-center">
        <div class="p-2 bg-green-100 rounded-lg">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Total Value</p>
            <p class="text-2xl font-semibold text-gray-900">${{ number_format($items->sum(fn($item) => $item->quantity * ($item->unit_price ?? 0)), 2) }}</p>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 flex items-center">
        <div class="p-2 bg-yellow-100 rounded-lg">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Low Stock</p>
            <p class="text-2xl font-semibold text-gray-900">
                {{ $items->filter(fn($item) => $item->quantity <= ($item->reorder_level ?? 0))->count() }}
            </p>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 flex items-center">
        <div class="p-2 bg-red-100 rounded-lg">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Out of Stock</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $items->where('quantity', 0)->count() }}</p>
        </div>
    </div>
</div>

<!-- Inventory Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Current Inventory Levels</h3>
        <p class="text-sm text-gray-500">Stock levels are automatically updated when items are added or removed</p>
    </div>

    @if($items->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($items as $item)
                    <tr class="hover:bg-blue-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <a href="{{ route('retail.inventory.show', $item->id) }}" class="text-sm font-medium text-blue-600 hover:underline">
                                    {{ $item->item_name }}
                                </a>
                                <div class="text-xs text-gray-400">{{ $item->item_type ?? 'N/A' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900">{{ $item->quantity }}</span>
                                <span class="text-sm text-gray-500 ml-1">{{ $item->unit ?? '' }}</span>
                            </div>
                            @if($item->reorder_level > 0)
                                <div class="text-xs text-gray-400">Reorder: {{ $item->reorder_level }} {{ $item->unit ?? '' }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                {{ $item->quantity === 0 ? 'bg-red-100 text-red-800' : ($item->quantity <= ($item->reorder_level ?? 0) ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                @if($item->quantity === 0)
                                    <svg class="w-3 h-3 mr-1 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Out of Stock
                                @elseif($item->quantity <= ($item->reorder_level ?? 0))
                                    <svg class="w-3 h-3 mr-1 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"></path></svg>
                                    Low Stock
                                @else
                                    <svg class="w-3 h-3 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Available
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ number_format($item->unit_price ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            ${{ number_format($item->quantity * ($item->unit_price ?? 0), 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item->updated_at ? $item->updated_at->diffForHumans() : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex space-x-2 justify-end">
                                <button onclick="updateQuantity({{ $item->id }}, '{{ addslashes($item->item_name) }}', {{ $item->quantity }})" class="inline-flex items-center text-green-600 hover:text-green-900 focus:outline-none focus:underline" aria-label="Update Quantity">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 3.5a2.121 2.121 0 113 3L7 19.5 3 21l1.5-4L16.5 3.5z"></path></svg>
                                    Update Qty
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- Pagination (if using) --}}
        <div class="px-6 py-4">
            {{ method_exists($items, 'links') ? $items->links() : '' }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No inventory items</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by adding your first inventory item.</p>
        </div>
    @endif
</div>

<!-- Quick Update Modal -->
<div id="quantityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-200">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-2xl rounded-md bg-white transition-all duration-200">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Quantity</h3>
            <form id="quantityForm" method="POST">
                @csrf
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
    document.getElementById('quantityForm').action = `/retail/inventory/update-quantity/${itemId}`;
    document.getElementById('quantityModal').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('quantity').focus();
    }, 200);
}

function closeModal() {
    document.getElementById('quantityModal').classList.add('hidden');
}

document.getElementById('quantityModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Hide loader when page is ready
window.addEventListener('DOMContentLoaded', function() {
    document.getElementById('pageLoader').style.display = 'none';
});
</script>
@endsection

<style>
@keyframes spin {
  to { transform: rotate(360deg); }
}
.animate-spin {
  animation: spin 1s linear infinite;
}
</style>
