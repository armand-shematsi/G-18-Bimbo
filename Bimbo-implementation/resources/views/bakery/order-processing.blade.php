@extends('layouts.bakery-manager')

@section('header')
<h1 class="text-3xl font-bold text-gray-900">Order Processing</h1>
<p class="mt-1 text-sm text-gray-600">Manage orders to suppliers and from retailers.</p>
@endsection

@section('content')
<div class="bg-gradient-to-r from-sky-500 via-purple-500 to-pink-400 rounded-lg shadow-lg mb-8 flex items-center justify-between px-10 py-10">
    <div>
        <h2 class="text-3xl font-bold text-white mb-2">Order Processing Overview</h2>
        <p class="text-lg text-sky-100">Manage supplier and retailer orders, track status, and streamline fulfillment</p>
    </div>
    <div>
        <svg class="w-24 h-24 text-sky-200" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
        </svg>
    </div>
</div>
<!-- New & Assigned Orders Section -->
<div class="bg-white rounded-xl shadow-lg mb-8 border-l-4 border-blue-600">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-900">New & Assigned Orders</h2>
        <span class="text-sm text-gray-500">(Pending & Processing)</span>
    </div>
    <div class="p-6">
        @if(isset($orders) && $orders->count())
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold">Order ID</th>
                        <th class="px-4 py-2 text-left font-semibold">Customer</th>
                        <th class="px-4 py-2 text-left font-semibold">Status</th>
                        <th class="px-4 py-2 text-left font-semibold">Placed At</th>
                        <th class="px-4 py-2 text-left font-semibold">Fulfillment</th>
                        <th class="px-4 py-2 text-left font-semibold">Delivery</th>
                        <th class="px-4 py-2 text-left font-semibold">Tracking #</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $order->id }}</td>
                        <td class="px-4 py-2">{{ $order->customer_name }}</td>
                        <td class="px-4 py-2 capitalize">{{ $order->status }}</td>
                        <td class="px-4 py-2">{{ $order->placed_at ? $order->placed_at->format('M d, Y H:i') : '-' }}</td>
                        <td class="px-4 py-2">{{ $order->fulfillment_type ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $order->delivery_option ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $order->tracking_number ?? '-' }}</td>
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
<div class="max-w-7xl mx-auto py-8"
    data-supplier-order-route="{{ route('bakery.order-processing.supplier-order') }}"
    data-retailer-orders-route="{{ route('bakery.order-processing.retailer-orders') }}">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Place Order to Supplier -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-blue-700 mb-4">Place Order to Supplier</h2>
            <form action="{{ route('bakery.order-processing.supplier-order') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Product</label>
                    <select name="product_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="">Select product</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Quantity</label>
                    <input type="number" name="quantity" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Enter quantity" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Supplier</label>
                    <select name="supplier_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="">Select supplier</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }} ({{ $supplier->email }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Place Order</button>
                <div id="supplierOrderMsg" class="mt-2 text-sm"></div>
            </form>
        </div>
        <!-- Receive Orders from Retailers -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-green-700 mb-4">Receive Orders from Retailers</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Retailer</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody id="retailerOrdersTbody" class="bg-white divide-y divide-gray-100">
                        @php $found = false; @endphp
                        @foreach($retailerOrders as $order)
                            @if($order->user && $order->user->role === 'retail_manager')
                                @foreach($order->items as $item)
                                    @if($item->product && $item->product->type === 'finished_product')
                                        @php $found = true; @endphp
                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->product->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <select class="order-status-dropdown border rounded px-2 py-1" data-order-id="{{ $order->id }}">
                                    @foreach(['pending', 'processing', 'shipped', 'received'] as $status)
                                        <option value="{{ $status }}" @if(strtolower($order->status) === $status) selected @endif>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <button 
                                    class="mark-as-received-btn bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition"
                                    data-order-id="{{ $order->id }}"
                                    type="button"
                                >
                                    Mark as Received
                                </button>
                            </td>
                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        @if(!$found)
                        <tr>
                                <td colspan="4" class="text-center text-gray-500 py-4">No retailer orders for finished products found.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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