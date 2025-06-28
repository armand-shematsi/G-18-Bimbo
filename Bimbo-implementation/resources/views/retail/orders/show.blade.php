@extends('layouts.retail-manager')

@section('header')
    Order #{{ $order->id }}
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded p-6">
            <h2 class="text-xl font-semibold mb-4">Order Details</h2>
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
            @endif
            <div class="mb-4">
                <strong>Status:</strong> <span class="px-2 py-1 rounded bg-gray-200">{{ ucfirst($order->status) }}</span>
                <form method="POST" action="{{ route('retail.orders.changeStatus', $order->id) }}" class="inline-block ml-4">
                    @csrf
                    <select name="status" class="border rounded px-2 py-1">
                        <option value="pending" @if($order->status=='pending') selected @endif>Pending</option>
                        <option value="processing" @if($order->status=='processing') selected @endif>Processing</option>
                        <option value="shipped" @if($order->status=='shipped') selected @endif>Shipped</option>
                        <option value="delivered" @if($order->status=='delivered') selected @endif>Delivered</option>
                        <option value="cancelled" @if($order->status=='cancelled') selected @endif>Cancelled</option>
                    </select>
                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded ml-2">Update Status</button>
                </form>
            </div>
            <div class="mb-4">
                <strong>Customer:</strong> {{ $order->user->name ?? 'N/A' }}<br>
                <strong>Placed At:</strong> {{ $order->placed_at ? $order->placed_at->format('Y-m-d H:i') : '-' }}<br>
                <strong>Total:</strong> ${{ number_format($order->total, 2) }}<br>
            </div>
            <h3 class="font-semibold mb-2">Order Items</h3>
            <table class="min-w-full divide-y divide-gray-200 mb-4">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Product</th>
                        <th class="px-4 py-2">Quantity</th>
                        <th class="px-4 py-2">Unit Price</th>
                        <th class="px-4 py-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-4 py-2">{{ $item->product_name }}</td>
                        <td class="px-4 py-2">{{ $item->quantity }}</td>
                        <td class="px-4 py-2">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-4 py-2">${{ number_format($item->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <h3 class="font-semibold mb-2">Payment</h3>
            @if($order->payment)
                <div>
                    <strong>Status:</strong> {{ ucfirst($order->payment->status) }}<br>
                    <strong>Method:</strong> {{ $order->payment->payment_method }}<br>
                    <strong>Transaction ID:</strong> {{ $order->payment->transaction_id }}<br>
                    <strong>Paid At:</strong> {{ $order->payment->paid_at }}<br>
                </div>
            @else
                <div>No payment recorded.</div>
            @endif
            <div class="mt-6 flex space-x-2">
                <a href="{{ route('retail.orders.edit', $order->id) }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">Edit</a>
                <form method="POST" action="{{ route('retail.orders.destroy', $order->id) }}" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Cancel Order</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 