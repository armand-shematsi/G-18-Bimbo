@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('bakery.inventory.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                    <i class="fas fa-arrow-left"></i> Back to Inventory
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $inventory->item_name }}</h1>
                    <p class="text-gray-600 mt-2">Inventory Item Details</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('bakery.inventory.edit', $inventory) }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <button onclick="openStockModal()"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Update Stock
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Item Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Item Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Item Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $inventory->item_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Item Type</label>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1
                            @if($inventory->item_type == 'ingredient') bg-blue-100 text-blue-800
                            @elseif($inventory->item_type == 'finished_good') bg-green-100 text-green-800
                            @elseif($inventory->item_type == 'packaging') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $inventory->item_type)) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Current Quantity</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $inventory->quantity }} {{ $inventory->unit }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unit Price</label>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($inventory->unit_price)
                                ${{ number_format($inventory->unit_price, 2) }}
                            @else
                                Not set
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Reorder Level</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $inventory->reorder_level }} {{ $inventory->unit }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Storage Location</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $inventory->location ?: 'Not specified' }}</p>
                    </div>

                    @if($inventory->product)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Associated Product</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $inventory->product->name }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p class="mt-1">
                            @if($inventory->quantity == 0)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Out of Stock
                                </span>
                            @elseif($inventory->quantity <= $inventory->reorder_level)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Low Stock
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Available
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Stock Movement History -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Stock Movement History</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Change</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($movements as $movement)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $movement->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($movement->movement_type == 'in') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($movement->movement_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="@if($movement->quantity_change > 0) text-green-600 @else text-red-600 @endif">
                                            {{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }} {{ $inventory->unit }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $movement->notes ?: '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $movement->user ? $movement->user->name : 'System' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        No movement history available.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($movements->hasPages())
                    <div class="px-6 py-3 border-t border-gray-200">
                        {{ $movements->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="space-y-6">
            <!-- Stock Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Stock Status</h3>

                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Current Stock</span>
                            <span class="font-medium">{{ $inventory->quantity }} {{ $inventory->unit }}</span>
                        </div>
                        <div class="mt-2 bg-gray-200 rounded-full h-2">
                            @php
                                $percentage = $inventory->reorder_level > 0 ? min(100, ($inventory->quantity / $inventory->reorder_level) * 100) : 0;
                            @endphp
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Reorder Level</span>
                            <span class="font-medium">{{ $inventory->reorder_level }} {{ $inventory->unit }}</span>
                        </div>
                    </div>

                    @if($inventory->unit_price)
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Value</span>
                                <span class="font-medium">${{ number_format($inventory->quantity * $inventory->unit_price, 2) }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>

                <div class="space-y-3">
                    <button onclick="openStockModal('in')"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>Stock In
                    </button>

                    <button onclick="openStockModal('out')"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200">
                        <i class="fas fa-minus mr-2"></i>Stock Out
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Update Modal -->
<div id="stockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Stock</h3>

            <form id="stockForm" action="{{ route('bakery.inventory.update-stock', $inventory) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="movement_type" class="block text-sm font-medium text-gray-700 mb-2">Movement Type</label>
                    <select name="movement_type" id="movement_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="in">Stock In</option>
                        <option value="out">Stock Out</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="quantity_change" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <input type="number"
                           name="quantity_change"
                           id="quantity_change"
                           min="0.01"
                           step="0.01"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="0.00">
                </div>

                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea name="notes"
                              id="notes"
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Reason for stock change..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button"
                            onclick="closeStockModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                        Update Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openStockModal(type = null) {
    document.getElementById('stockModal').classList.remove('hidden');
    if (type) {
        document.getElementById('movement_type').value = type;
    }
}

function closeStockModal() {
    document.getElementById('stockModal').classList.add('hidden');
    document.getElementById('stockForm').reset();
}

// Close modal when clicking outside
document.getElementById('stockModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStockModal();
    }
});
</script>

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
