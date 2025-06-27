@extends('layouts.retail-manager')

@section('header')
    Orders
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between mb-4">
            <form method="GET" action="" class="flex space-x-2">
                <input type="text" name="search" placeholder="Search orders..." class="border rounded px-2 py-1">
                <select name="status" class="border rounded px-2 py-1">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <button type="submit" class="bg-primary text-white px-3 py-1 rounded">Filter</button>
            </form>
            <a href="{{ route('retail.orders.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">+ Create Order</a>
        </div>
        <form method="POST" action="#">
            @csrf
            <div class="overflow-x-auto bg-white shadow rounded">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2"><input type="checkbox" onclick="toggleAll(this)"></th>
                            <th class="px-4 py-2">Order #</th>
                            <th class="px-4 py-2">Customer</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Total</th>
                            <th class="px-4 py-2">Placed At</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="px-4 py-2"><input type="checkbox" name="orders[]" value="{{ $order->id }}"></td>
                            <td class="px-4 py-2">{{ $order->id }}</td>
                            <td class="px-4 py-2">{{ $order->user->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ ucfirst($order->status) }}</td>
                            <td class="px-4 py-2">${{ number_format($order->total, 2) }}</td>
                            <td class="px-4 py-2">{{ $order->placed_at ? $order->placed_at->format('Y-m-d H:i') : '-' }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('retail.orders.show', $order->id) }}" class="text-blue-600 hover:underline">View</a>
                                <a href="{{ route('retail.orders.edit', $order->id) }}" class="text-yellow-600 hover:underline ml-2">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-center">No orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Bulk actions stub -->
            <div class="mt-4 flex space-x-2">
                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded">Cancel Selected</button>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Mark as Shipped</button>
            </div>
        </form>
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
<script>
function toggleAll(source) {
    checkboxes = document.getElementsByName('orders[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
</script>
@endsection 