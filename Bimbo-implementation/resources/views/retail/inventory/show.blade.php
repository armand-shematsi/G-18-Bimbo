@extends('layouts.retail-manager')

@section('header')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $inventory->item_name }}</h1>
        <p class="text-sm text-gray-500">Inventory details and adjustment/order history</p>
        <a href="{{ route('retail.inventory.check') }}" class="text-blue-600 hover:underline">&larr; Back to Inventory</a>
    </div>
@endsection

@section('content')
    <div class="bg-white p-6 rounded shadow mb-8">
        <h2 class="text-lg font-semibold mb-4">Item Details</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><strong>Type:</strong> {{ $inventory->item_type ?? 'N/A' }}</div>
            <div><strong>Quantity:</strong> {{ $inventory->quantity }} {{ $inventory->unit }}</div>
            <div><strong>Unit Price:</strong> ${{ number_format($inventory->unit_price, 2) }}</div>
            <div><strong>Total Value:</strong> ${{ number_format($inventory->quantity * $inventory->unit_price, 2) }}</div>
            <div><strong>Reorder Level:</strong> {{ $inventory->reorder_level }}</div>
            <div><strong>Status:</strong> {{ $inventory->quantity === 0 ? 'Out of Stock' : ($inventory->quantity <= ($inventory->reorder_level ?? 0) ? 'Low Stock' : 'Available') }}</div>
            <div><strong>Last Updated:</strong> {{ $inventory->updated_at ? $inventory->updated_at->diffForHumans() : 'N/A' }}</div>
        </div>
    </div>

    <div>
        <h3 class="text-xl font-semibold mb-2">Order History Affecting This Inventory</h3>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">Order #</th>
                    <th class="px-4 py-2 border">Date</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Quantity Deducted</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orderItems as $item)
                    <tr>
                        <td class="px-4 py-2 border">
                            <a href="{{ route('retail.orders.show', $item->order_id) }}" class="text-blue-600 underline">#{{ $item->order_id }}</a>
                        </td>
                        <td class="px-4 py-2 border">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2 border">{{ $item->order->status ?? '-' }}</td>
                        <td class="px-4 py-2 border">{{ $item->quantity }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-2 border text-center text-gray-500">No orders have affected this inventory yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
