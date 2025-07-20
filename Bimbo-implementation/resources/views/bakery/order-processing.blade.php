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
<!-- Restore the original 'New & Assigned Orders' table and 'Receive Orders from Retailers' table, removing the merged table. -->
<div class="bg-white rounded-xl shadow-lg mb-8 border-l-4 border-blue-600">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-900">Retailer Orders</h2>
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
                        <th class="px-4 py-2 text-left font-semibold">Products</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($retailerOrders as $order)
                        @if(!is_object($order))
                            <tr>
                                <td colspan="5" style="color:red;">DEBUG: Non-object order: {{ var_export($order, true) }}</td>
                            </tr>
                            @continue
                        @endif
                        @php $products = []; @endphp
                        @if(is_object($order) && is_iterable($order->items))
                            @foreach($order->items as $idx => $item)
                                @if(is_object($item) && is_object($item->product) && isset($item->product->id) && is_int($item->product->id) && $item->product->type === 'finished_product')
                                    @php $products[] = ($item->product->name ?? 'N/A') . ' (' . $item->quantity . ')'; @endphp
                                @elseif(!is_object($item))
                                    <tr>
                                        <td colspan="5" style="color:red;">DEBUG: Non-object item in order #{{ $order->id }}: {{ var_export($item, true) }}</td>
                                    </tr>
                                @elseif(!is_object($item->product))
                                    <tr>
                                        <td colspan="5" style="color:red;">DEBUG: Non-object product in order #{{ $order->id }}: {{ var_export($item->product, true) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                        @if(is_object($order) && count($products) > 0)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $order->id }}</td>
                            <td class="px-4 py-2">
                                @if(is_object($order->user))
                                    {{ $order->user->name ?? 'N/A' }}
                                @else
                                    <span style="color:red;">User missing</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
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
                            <td class="px-4 py-2">{{ $order->placed_at ? $order->placed_at->format('M d, Y H:i') : '-' }}</td>
                            <td class="px-4 py-2">
                                @if(is_array($products) || is_object($products))
                                    {{ implode(', ', $products) }}
                                @else
                                    <span style="color:red;">Invalid products data</span>
                                @endif
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center text-gray-500 py-8">
            <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <p class="mt-2 text-sm">No retailer orders at the moment.</p>
        </div>
        @endif
    </div>
</div>
        <!-- Place Order to Supplier and Supplier Orders Table -->
        <div class="flex-1">
            <!-- Supplier Orders List -->
            <div class="supplier-orders-card">
                <h2 class="supplier-orders-title">
                    <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6" /></svg>
                    Your Supplier Orders
                </h2>
                <div class="overflow-x-auto">
                    <table class="supplier-orders-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Placed At</th>
                            </tr>
                        </thead>
                        <tbody id="supplierOrdersTableBody">
                            @forelse($supplierOrders as $order)
                            <tr>
                                <td>{{ is_object($order) ? $order->id : $order }}</td>
                                <td>{{ (is_object($order) && is_object($order->product)) ? $order->product->name : '' }}</td>
                                <td>{{ is_object($order) ? $order->quantity : '' }}</td>
                                <td>
                                    @if(is_object($order))
                                        <span class="status-badge {{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if(is_object($order) && is_object($order->product) && $order->product->unit_price !== null)
                                        {{ number_format($order->quantity * $order->product->unit_price, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ (is_object($order) && $order->created_at) ? $order->created_at->format('n/j/Y, g:i A') : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 py-4">No supplier orders at the moment.</td>
                            </tr>
                            @endforelse
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

@push('styles')
<style>
/* Professional Supplier Orders Card/Table Styles */
.supplier-orders-card {
    background: linear-gradient(135deg, #f3e8ff 0%, #e0e7ff 100%);
    border-radius: 24px;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.12);
    padding: 2.5rem 2rem 2rem 2rem;
    margin-bottom: 2rem;
    border: 1px solid #e0e7ff;
}
.supplier-orders-title {
    font-size: 2.1rem;
    font-weight: 800;
    color: #7c3aed;
    margin-bottom: 1.5rem;
    letter-spacing: -1px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.supplier-orders-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(124, 58, 237, 0.04);
}
.supplier-orders-table th, .supplier-orders-table td {
    padding: 1rem 1.2rem;
    text-align: left;
    font-size: 1.05rem;
}
.supplier-orders-table th {
    background: #f3e8ff;
    color: #6d28d9;
    font-weight: 700;
    border-bottom: 2px solid #e0e7ff;
}
.supplier-orders-table tr:nth-child(even) {
    background: #f8fafc;
}
.supplier-orders-table tr:hover {
    background: #ede9fe;
    transition: background 0.2s;
}
.status-badge {
    display: inline-block;
    padding: 0.35em 0.9em;
    border-radius: 999px;
    font-size: 0.95em;
    font-weight: 600;
    letter-spacing: 0.02em;
    background: #f3e8ff;
    color: #7c3aed;
    box-shadow: 0 1px 2px rgba(124, 58, 237, 0.07);
}
.status-badge.pending { background: #fef3c7; color: #b45309; }
.status-badge.completed { background: #d1fae5; color: #047857; }
.status-badge.processing { background: #e0e7ff; color: #3730a3; }
.status-badge.cancelled { background: #fee2e2; color: #b91c1c; }
@media (max-width: 700px) {
    .supplier-orders-card { padding: 1.2rem 0.5rem; }
    .supplier-orders-title { font-size: 1.3rem; }
    .supplier-orders-table th, .supplier-orders-table td { padding: 0.6rem 0.5rem; font-size: 0.95rem; }
}
</style>
@endpush