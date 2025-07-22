@extends('layouts.supplier')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Inventory Item') }}
        </h2>
    </div>
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            @if(session('success'))
                <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('supplier.inventory.update', $item->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="item_name" class="block text-sm font-medium text-gray-700">Item Name</label>
                    <input type="text" name="item_name" id="item_name" value="{{ old('item_name', $item->item_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="item_type" class="block text-sm font-medium text-gray-700">Item Type</label>
                    <input type="text" name="item_type" id="item_type" value="{{ old('item_type', $item->item_type) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" name="quantity" id="quantity" min="0" step="1" value="{{ old('quantity', $item->quantity) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700">Unit</label>
                    <select name="unit" id="unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Select a unit</option>
                        <option value="kg" {{ old('unit', $item->unit) == 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                        <option value="g" {{ old('unit', $item->unit) == 'g' ? 'selected' : '' }}>Grams (g)</option>
                        <option value="l" {{ old('unit', $item->unit) == 'l' ? 'selected' : '' }}>Liters (l)</option>
                        <option value="ml" {{ old('unit', $item->unit) == 'ml' ? 'selected' : '' }}>Milliliters (ml)</option>
                        <option value="pcs" {{ old('unit', $item->unit) == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Select a status</option>
                        <option value="available" {{ old('status', $item->status) == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="low_stock" {{ old('status', $item->status) == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ old('status', $item->status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>

                <div>
                    <label for="reorder_level" class="block text-sm font-medium text-gray-700">Reorder Level</label>
                    <input type="number" name="reorder_level" id="reorder_level" min="0" step="1" value="{{ old('reorder_level', $item->reorder_level) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="unit_price" class="block text-sm font-medium text-gray-700">Unit Price</label>
                    <input type="number" name="unit_price" id="unit_price" min="0" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('unit_price', $item->unit_price) }}" required>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('supplier.inventory.index') }}" class="inline-flex items-center px-4 py-2 border border-blue-500 rounded-md font-semibold text-xs text-blue-700 uppercase tracking-widest shadow-sm hover:bg-blue-50 hover:text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('movement_history')
    <div class="bg-white mt-8 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h3 class="font-semibold text-lg mb-4">Movement History</h3>
            @if($movements->isEmpty())
                <p class="text-gray-500">No movement history available for this item.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Note</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($movements as $movement)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap capitalize">{{ $movement->type }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $movement->quantity }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $movement->user ? $movement->user->name : 'N/A' }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $movement->note }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@yield('movement_history')
