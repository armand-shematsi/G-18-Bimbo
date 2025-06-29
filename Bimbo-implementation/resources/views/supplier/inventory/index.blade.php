@extends('layouts.supplier')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Raw Material Inventory') }}
        </h2>
        <a href="{{ route('supplier.inventory.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add New Item
        </a>
    </div>
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-blue-600">Total Items</div>
                    <div class="text-2xl font-bold text-blue-900">{{ $inventory->count() }}</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-green-600">Available</div>
                    <div class="text-2xl font-bold text-green-900">{{ $inventory->where('status', 'available')->count() }}</div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-yellow-600">Low Stock</div>
                    <div class="text-2xl font-bold text-yellow-900">{{ $inventory->where('status', 'low_stock')->count() }}</div>
                </div>
                <div class="bg-red-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-red-600">Out of Stock</div>
                    <div class="text-2xl font-bold text-red-900">{{ $inventory->where('status', 'out_of_stock')->count() }}</div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reorder Level</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($inventory as $item)
                        <tr class="{{ $item->needsReorder() ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->item_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->item_type ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-900">{{ $item->quantity }}</span>
                                    <button onclick="showQuantityModal({{ $item->id }}, {{ $item->quantity }})" class="text-blue-600 hover:text-blue-900 text-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->unit }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $item->status === 'available' ? 'bg-green-100 text-green-800' : ($item->status === 'low_stock' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                                @if($item->needsReorder())
                                    <span class="ml-2 inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Reorder Needed</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->reorder_level }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="{{ route('supplier.inventory.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                <form action="{{ route('supplier.inventory.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                No inventory items found. <a href="{{ route('supplier.inventory.create') }}" class="text-blue-600 hover:text-blue-900">Add your first item</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quantity Update Modal -->
    <div id="quantityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Quantity</h3>
                <form id="quantityForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="quantity" class="block text-sm font-medium text-gray-700">New Quantity</label>
                        <input type="number" id="quantity" name="quantity" min="0" step="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hideQuantityModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showQuantityModal(itemId, currentQuantity) {
            document.getElementById('quantity').value = currentQuantity;
            document.getElementById('quantityForm').action = `/supplier/inventory/${itemId}/update-quantity`;
            document.getElementById('quantityModal').classList.remove('hidden');
        }

        function hideQuantityModal() {
            document.getElementById('quantityModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('quantityModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideQuantityModal();
            }
        });
    </script>
@endsection
