@extends('layouts.supplier')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Remove Stock') }}
    </h2>
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg max-w-lg mx-auto mt-6">
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('supplier.stockout.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="inventory_id" class="block text-sm font-medium text-gray-700">Inventory Item</label>
                    <select id="inventory_id" name="inventory_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Select Item</option>
                        @foreach($inventory as $item)
                            <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="quantity_removed" class="block text-sm font-medium text-gray-700">Quantity to Remove</label>
                    <input type="number" id="quantity_removed" name="quantity_removed" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="removed_at" class="block text-sm font-medium text-gray-700">Removal Date</label>
                    <input type="date" id="removed_at" name="removed_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Remove Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection 