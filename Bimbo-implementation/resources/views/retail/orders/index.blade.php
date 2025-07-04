@extends('layouts.retail-manager')

@section('header')
    Orders
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-100 to-green-200 py-10">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-extrabold text-blue-800 tracking-tight">Orders</h1>
            <a href="{{ route('retail.orders.create') }}" class="bg-gradient-to-r from-green-500 to-blue-600 text-white px-6 py-3 rounded-xl shadow-lg font-bold text-lg hover:from-green-600 hover:to-blue-700 transition-all duration-200">+ Create Order</a>
        </div>
        <div class="bg-white rounded-2xl shadow-2xl border border-blue-200 p-8">
            <div class="flex flex-col md:flex-row md:space-x-4 mb-6">
                <input type="text" placeholder="Search orders..." class="mb-4 md:mb-0 flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-400 focus:ring-blue-400 bg-blue-50 px-4 py-2 text-lg" />
                <select class="rounded-lg border-gray-300 shadow-sm focus:border-green-400 focus:ring-green-400 bg-green-50 px-4 py-2 text-lg">
                    <option>All Statuses</option>
                    <!-- Add more statuses as needed -->
                </select>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-blue-200">
                    <thead>
                        <tr class="bg-gradient-to-r from-blue-700 to-green-600 text-white">
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Order #</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Tracking Number</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Placed At</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-blue-100">
                        @foreach($orders as $order)
                        <tr class="hover:bg-blue-50 hover:scale-[1.01] hover:shadow-lg transition-all duration-150 ease-in-out">
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-blue-700">{{ $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-green-800 font-semibold">{{ $order->customer_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 text-sm font-bold rounded-full 
                                    {{ $order->status === 'pending' ? 'bg-yellow-200 text-yellow-900' : 
                                       ($order->status === 'completed' ? 'bg-green-200 text-green-900' : 
                                       ($order->status === 'cancelled' ? 'bg-red-200 text-red-900' : 'bg-blue-200 text-blue-900')) }}">
                                    @if($order->status === 'pending')
                                        <svg class="w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" /></svg>
                                    @elseif($order->status === 'completed')
                                        <svg class="w-4 h-4 mr-1 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    @elseif($order->status === 'cancelled')
                                        <svg class="w-4 h-4 mr-1 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    @else
                                        <svg class="w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" /></svg>
                                    @endif
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-blue-900">{{ $order->tracking_number ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-blue-900">${{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $order->placed_at }}</td>
                            <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                                <a href="{{ route('retail.orders.show', $order->id) }}" class="group relative text-blue-600 font-bold hover:underline mr-2 focus:outline-none focus:ring-2 focus:ring-blue-400 rounded transition-transform duration-100 hover:scale-110" tabindex="0">
                                    <span class="absolute left-1/2 -translate-x-1/2 -top-8 opacity-0 group-hover:opacity-100 group-focus:opacity-100 bg-blue-700 text-white text-xs rounded px-2 py-1 pointer-events-none transition-all duration-150">View Order</span>
                                    <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    View
                                </a>
                                <a href="{{ route('retail.orders.edit', $order->id) }}" class="group relative text-orange-500 font-bold hover:underline focus:outline-none focus:ring-2 focus:ring-orange-400 rounded transition-transform duration-100 hover:scale-110" tabindex="0">
                                    <span class="absolute left-1/2 -translate-x-1/2 -top-8 opacity-0 group-hover:opacity-100 group-focus:opacity-100 bg-orange-600 text-white text-xs rounded px-2 py-1 pointer-events-none transition-all duration-150">Edit Order</span>
                                    <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5h2m-1 0v14m-7-7h14" /></svg>
                                    Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex space-x-4 mt-8">
                <button class="bg-gradient-to-r from-red-500 to-red-700 text-white px-6 py-2 rounded-lg font-bold shadow hover:from-red-600 hover:to-red-800 transition active:scale-95">Cancel Selected</button>
                <button class="bg-gradient-to-r from-blue-500 to-green-500 text-white px-6 py-2 rounded-lg font-bold shadow hover:from-blue-600 hover:to-green-600 transition active:scale-95">Mark as Shipped</button>
            </div>
            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                {{ $orders->links() }}
            </div>
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