@extends('layouts.supplier')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Stock Out Records') }}
    </h2>
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg max-w-3xl mx-auto mt-6">
        <div class="p-6 text-gray-900">
            <a href="{{ route('supplier.stockout.create') }}" class="mb-4 inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Remove Stock</a>
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inventory Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Removed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Removal Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($stockOuts as $stockOut)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $stockOut->inventory->item_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $stockOut->quantity_removed }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $stockOut->removed_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">No stock out records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection 