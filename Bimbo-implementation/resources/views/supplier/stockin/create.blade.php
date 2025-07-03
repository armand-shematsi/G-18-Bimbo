@extends('layouts.supplier')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Add Stock In') }}
    </h2>
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg max-w-lg mx-auto mt-6">
        <div class="p-6 text-gray-900">
            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('supplier.stockin.store') }}">
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
                    <label for="quantity_received" class="block text-sm font-medium text-gray-700">Quantity Received</label>
                    <input type="number" id="quantity_received" name="quantity_received" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="received_at" class="block text-sm font-medium text-gray-700">Received Date</label>
                    <input type="date" id="received_at" name="received_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Add Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
