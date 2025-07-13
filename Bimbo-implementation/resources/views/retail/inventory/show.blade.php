@extends('layouts.retail-manager')

@section('header')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">{{ $item->item_name }}</h1>
    <p class="text-sm text-gray-500">Inventory details and adjustment/order history</p>
    <a href="{{ route('retail.inventory.check') }}" class="text-blue-600 hover:underline">&larr; Back to Inventory</a>
</div>
@endsection

@section('content')
<div class="bg-white p-6 rounded shadow mb-8">
    <h2 class="text-lg font-semibold mb-4">Item Details</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><strong>Type:</strong> {{ $item->item_type ?? 'N/A' }}</div>
        <div><strong>Quantity:</strong> {{ $item->quantity }} {{ $item->unit }}</div>
        <div><strong>Unit Price:</strong> ${{ number_format($item->unit_price, 2) }}</div>
        <div><strong>Total Value:</strong> ${{ number_format($item->quantity * $item->unit_price, 2) }}</div>
        <div><strong>Reorder Level:</strong> {{ $item->reorder_level }}</div>
        <div><strong>Status:</strong> {{ $item->quantity === 0 ? 'Out of Stock' : ($item->quantity <= ($item->reorder_level ?? 0) ? 'Low Stock' : 'Available') }}</div>
        <div><strong>Last Updated:</strong> {{ $item->updated_at ? $item->updated_at->diffForHumans() : 'N/A' }}</div>
    </div>
</div>

<div class="bg-white p-6 rounded shadow">
    <h2 class="text-lg font-semibold mb-4">Order/Adjustment History</h2>
    @if($orders->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($orders as $order)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->status ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->quantity ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at ? $order->created_at->toDateString() : 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No order or adjustment history found for this item.</p>
    @endif
</div>
@endsection
