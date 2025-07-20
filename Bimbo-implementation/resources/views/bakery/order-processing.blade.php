@extends('layouts.bakery-manager')

@section('header')
<div class="sticky top-0 z-30 bg-gradient-to-r from-blue-600 via-purple-500 to-pink-400 shadow-lg py-4 px-8 flex items-center justify-between rounded-b-2xl">
    <h1 class="text-3xl font-extrabold text-white flex items-center gap-3">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" /></svg>
        Order Processing
    </h1>
    <span class="text-lg text-white/80 font-semibold">Bakery Dashboard</span>
</div>
<p class="mt-1 text-sm text-gray-600">Manage orders to suppliers and from retailers.</p>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 pb-16"
     data-supplier-order-route="{{ url('/bakery/order-processing/supplier-order') }}"
     data-supplier-orders-route="{{ url('/order-processing/supplier-orders') }}">
    <div class="max-w-7xl mx-auto py-8">
        <div class="flex flex-col md:flex-row gap-8">
<!-- New & Assigned Orders Section -->
<div class="bg-white rounded-xl shadow-lg mb-8 border-l-4 border-blue-600">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-900">New & Assigned Orders</h2>
        <span class="text-sm text-gray-500">(Pending & Processing)</span>
    </div>
    <div class="p-6">
        @if(isset($retailerOrders) && $retailerOrders->count())
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold">Order ID</th>
                        <th class="px-4 py-2 text-left font-semibold">Retailer</th>
                        <th class="px-4 py-2 text-left font-semibold">Status</th>
                        <th class="px-4 py-2 text-left font-semibold">Placed At</th>
                        <th class="px-4 py-2 text-left font-semibold">Product(s)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($retailerOrders as $order)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $order->id }}</td>
                            <td class="px-4 py-2">{{ $order->user->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 capitalize">{{ $order->status }}</td>
                            <td class="px-4 py-2">{{ $order->placed_at ? $order->placed_at->format('M d, Y H:i') : '-' }}</td>
                            <td class="px-4 py-2">
                                @foreach($order->items as $item)
                                    @if($item->product && $item->product->type === 'finished_product')
                                        {{ $item->product->name ?? 'N/A' }} ({{ $item->quantity }})<br>
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center text-gray-500 py-8">
            <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <p class="mt-2 text-sm">No new or assigned orders at the moment.</p>
        </div>
        @endif
    </div>
</div>
        <!-- Place Order to Supplier and Supplier Orders Table -->
        <div class="flex-1">
                <div class="bg-gradient-to-br from-blue-100 via-white to-purple-100 rounded-2xl shadow-xl p-6 border border-blue-200 mb-8">
                    <h2 class="text-2xl font-bold text-blue-700 mb-6 flex items-center gap-2">
                        <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v4a1 1 0 001 1h3m10-5v4a1 1 0 01-1 1h-3m-4 4h4m-2 0v4m0 0h-2m2 0h2" /></svg>
                        Place Order to Supplier
                    </h2>
                    <form id="supplierOrderForm" action="{{ route('bakery.order-processing.supplier-order') }}" method="POST" class="space-y-4">
                    @csrf
                        <div>
                        <label class="block text-gray-700 font-semibold mb-2">Product</label>
                            <select name="product_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                            <option value="">Select product</option>
                            @foreach($rawMaterials as $material)
                            <option value="{{ $material->id }}">{{ $material->item_name }} ({{ $material->quantity }} {{ $material->unit }})</option>
                            @endforeach
                        </select>
                    </div>
                        <div>
                        <label class="block text-gray-700 font-semibold mb-2">Quantity</label>
                            <input type="number" name="quantity" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-200" placeholder="Enter quantity" required>
                    </div>
                        <div>
                        <label class="block text-gray-700 font-semibold mb-2">Supplier</label>
                            <select name="supplier_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                            <option value="">Select supplier</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }} ({{ $supplier->email }})</option>
                            @endforeach
                        </select>
                    </div>
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-purple-500 text-white px-6 py-2 rounded-lg font-semibold shadow hover:scale-105 hover:from-blue-700 hover:to-purple-600 transition">Place Order</button>
                    <div id="supplierOrderMsg" class="mt-2 text-sm"></div>
                </form>
            </div>
            <!-- Supplier Orders List -->
                <div class="bg-gradient-to-br from-purple-100 via-white to-blue-100 rounded-2xl shadow-xl p-6 border border-purple-200">
                    <h2 class="text-2xl font-bold text-purple-700 mb-6 flex items-center gap-2">
                        <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6" /></svg>
                        Your Supplier Orders
                    </h2>
                <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold">Order ID</th>
                                    <th class="px-4 py-3 text-left font-semibold">Product</th>
                                    <th class="px-4 py-3 text-left font-semibold">Quantity</th>
                                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                                    <th class="px-4 py-3 text-left font-semibold">Total</th>
                                    <th class="px-4 py-3 text-left font-semibold">Placed At</th>
                            </tr>
                        </thead>
                        <tbody id="supplierOrdersTableBody">
                            <!-- Supplier orders will be rendered here by JS -->
                        </tbody>
                    </table>
            </div>
        </div>
        <!-- Receive Orders from Retailers -->
                <div class="bg-gradient-to-br from-green-100 via-white to-blue-100 rounded-2xl shadow-xl p-6 border border-green-200">
                    <h2 class="text-2xl font-bold text-green-700 mb-6 flex items-center gap-2">
                        <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" /></svg>
                        Receive Orders from Retailers
                    </h2>
                <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                    <th class="px-4 py-3 text-left font-semibold">Retailer</th>
                                    <th class="px-4 py-3 text-left font-semibold">Product</th>
                                    <th class="px-4 py-3 text-left font-semibold">Quantity</th>
                                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                            </tr>
                        </thead>
                            <tbody>
                                @foreach($retailerOrders as $order)
                                @foreach($order->items as $item)
                                    @if($order->user && $order->user->role === 'retail_manager' && $item->product && $item->product->type === 'finished_product')
                                            <tr class="hover:bg-green-50 transition @if($loop->parent->iteration % 2 == 0) bg-gray-50 @endif">
                                                <td class="px-4 py-3 font-medium text-gray-900 flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                    {{ $order->user->name }}
                                            </td>
                                                <td class="px-4 py-3">{{ $item->product->name ?? 'N/A' }}</td>
                                                <td class="px-4 py-3">{{ $item->quantity }}</td>
                                                <td class="px-4 py-3">
                                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold
                                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                                        @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                                        @elseif($order->status === 'received') bg-green-100 text-green-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                        @if($order->status === 'pending')
                                                            <svg class="w-4 h-4 inline text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" /><path d="M12 6v6l4 2" /></svg>
                                                        @elseif($order->status === 'processing')
                                                            <svg class="w-4 h-4 inline text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3" /></svg>
                                                        @elseif($order->status === 'shipped')
                                                            <svg class="w-4 h-4 inline text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 10h1l2 7h13l2-7h1" /></svg>
                                                        @elseif($order->status === 'received')
                                                            <svg class="w-4 h-4 inline text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" /></svg>
                                                        @endif
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                            </td>
                                        </tr>
                                    @endif
                                    @endforeach
                                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    <footer class="mt-12 text-center text-gray-500 text-sm py-6">
        &copy; {{ date('Y') }} Bimbo Bakery Management. All rights reserved.
    </footer>
</div>
@endsection

@push('scripts')
<script>
    var updateOrderStatusUrl = "{{ url('/bakery/order-processing/retailer-orders') }}";
    var csrfToken = "{{ csrf_token() }}";

    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('order-status-dropdown')) {
            const dropdown = e.target;
            const orderId = dropdown.getAttribute('data-order-id');
            const newStatus = dropdown.value;
            dropdown.disabled = true;
            fetch(`${updateOrderStatusUrl}/${orderId}/status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Optionally show a message or update the row
                } else {
                    alert('Failed to update status: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(err => {
                alert('AJAX error: ' + err);
            })
            .finally(() => {
                dropdown.disabled = false;
            });
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('mark-as-received-btn')) {
            const button = e.target;
            const orderId = button.getAttribute('data-order-id');
            button.disabled = true;
            fetch(`${updateOrderStatusUrl}/${orderId}/status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: 'received' })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update the dropdown to 'received'
                    const row = button.closest('tr');
                    if (row) {
                        const dropdown = row.querySelector('.order-status-dropdown');
                        if (dropdown) {
                            dropdown.value = 'received';
                        }
                    }
                    // Change button text and keep it disabled
                    button.textContent = 'Received';
                    button.disabled = true;
                } else {
                    alert('Failed to update status: ' + (data.message || 'Unknown error'));
                    button.disabled = false;
                }
            })
            .catch(err => {
                alert('AJAX error: ' + err);
                button.disabled = false;
            });
        }
    });
</script>
<script src="{{ asset('js/order-processing.js') }}"></script>
@endpush
