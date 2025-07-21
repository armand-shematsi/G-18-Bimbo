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
<div class="min-h-screen font-sans bg-gradient-to-br from-blue-100 via-purple-50 to-white pb-16 text-gray-800 text-[1.08rem]">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-14">
            <!-- Retailer Orders Card -->
            <div class="flex-1 min-w-0 bg-white rounded-3xl shadow-2xl border-l-8 border-blue-700 p-12 mb-14">
                <div class="mb-10">
                    <h2 class="text-4xl font-extrabold tracking-wider bg-gradient-to-r from-blue-700 via-blue-400 to-purple-400 bg-clip-text text-transparent drop-shadow-lg flex items-center gap-3">
                        <svg class="w-10 h-10 text-blue-400 drop-shadow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" /></svg>
                        Retailer Orders
                    </h2>
                    <div class="h-1 w-20 bg-gradient-to-r from-blue-500 to-purple-400 rounded mt-3 mb-3"></div>
                    <span class="text-lg text-gray-500 font-semibold tracking-wide">(Pending & Processing)</span>
    </div>
                <div class="overflow-x-auto rounded-xl mb-4">
                    <table class="min-w-full divide-y divide-blue-200 text-base bg-white rounded-xl">
                        <thead class="bg-gradient-to-r from-blue-200 via-blue-100 to-purple-100">
                            <tr>
                                <th class="px-5 py-4 text-left font-black text-blue-800 text-lg uppercase tracking-wider border-l-4 border-blue-600 whitespace-nowrap">Order ID</th>
                                <th class="px-5 py-4 text-left font-black text-blue-800 text-lg uppercase tracking-wider whitespace-nowrap">Retailer</th>
                                <th class="px-5 py-4 text-left font-black text-blue-800 text-lg uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="px-5 py-4 text-left font-black text-blue-800 text-lg uppercase tracking-wider whitespace-nowrap">Placed At</th>
                                <th class="px-5 py-4 text-left font-black text-blue-800 text-lg uppercase tracking-wider whitespace-nowrap">Products</th>
                    </tr>
                </thead>
                        <tbody class="divide-y divide-blue-50">
                    @foreach($retailerOrders as $order)
                                @if(!is_object($order))
                            @continue
                        @endif
                        @php $products = []; @endphp
                                @if(is_object($order) && is_iterable($order->items))
                        @foreach($order->items as $idx => $item)
                                        @if(is_object($item) && is_object($item->product) && isset($item->product->id) && is_int($item->product->id) && $item->product->type === 'finished_product')
                                @php $products[] = ($item->product->name ?? 'N/A') . ' (' . $item->quantity . ')'; @endphp
                            @endif
                        @endforeach
                                @endif
                                @if(is_object($order) && count($products) > 0)
                                <tr class="hover:bg-blue-100 hover:shadow-lg transition group">
                                    <td class="px-5 py-3 font-extrabold text-blue-900 whitespace-nowrap border-l-4 border-blue-600 bg-blue-50 text-lg">{{ $order->id }}</td>
                                    <td class="px-5 py-3 text-blue-800 font-medium">
                                        @if(is_object($order->user))
                                            <span>{{ $order->user->name ?? 'N/A' }}</span>
                                        @else
                                            <span class="text-gray-400 italic">User missing</span>
                                @endif
                            </td>
                                    <td class="px-5 py-3">
                                        <span class="inline-flex items-center gap-2 px-4 py-1 rounded-full text-base font-bold
                                            @if($order->status === 'pending') bg-yellow-200 text-yellow-900 animate-pulse
                                            @elseif($order->status === 'processing') bg-blue-200 text-blue-900
                                            @elseif($order->status === 'shipped') bg-purple-200 text-purple-900
                                            @elseif($order->status === 'received') bg-green-200 text-green-900
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if($order->status === 'pending')
                                                <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" /><path d="M12 6v6l4 2" /></svg>
                                    @elseif($order->status === 'processing')
                                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3" /></svg>
                                    @elseif($order->status === 'shipped')
                                                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 10h1l2 7h13l2-7h1" /></svg>
                                    @elseif($order->status === 'received')
                                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" /></svg>
                                    @endif
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                                    <td class="px-5 py-3 text-blue-800 whitespace-nowrap font-medium">{{ $order->placed_at ? $order->placed_at->format('M d, Y H:i') : '-' }}</td>
                                    <td class="px-5 py-3">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($products as $prod)
                                                <span class="inline-block bg-blue-200 text-blue-900 rounded-full px-3 py-1 text-xs font-semibold">{{ $prod }}</span>
                                            @endforeach
                                        </div>
                            </td>
                        </tr>
                                @endif
                    @endforeach
                </tbody>
            </table>
        </div>
            </div>
            <!-- Supplier Orders Card -->
            <div class="flex-1 min-w-0 w-full bg-gradient-to-br from-purple-100 to-blue-50 rounded-3xl shadow-2xl border-l-8 border-purple-700 p-12 mb-14">
                <div class="mb-10">
                    <h2 class="text-4xl font-extrabold tracking-wider bg-gradient-to-r from-purple-700 via-purple-400 to-blue-400 bg-clip-text text-transparent drop-shadow-lg flex items-center gap-3">
                        <svg class="w-10 h-10 text-purple-400 drop-shadow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6" /></svg>
                        Your Supplier Orders
                    </h2>
                    <div class="h-1 w-20 bg-gradient-to-r from-purple-500 to-blue-400 rounded mt-3 mb-3"></div>
                </div>
                <div class="overflow-x-auto rounded-xl mb-4 scroll-hint-table relative" x-data>
                    <template x-if="$el.dataset.scrollHint === 'true'">
                        <div class="absolute right-2 top-2 z-20 bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold shadow">Scroll for more &rarr;</div>
                    </template>
                    <table class="w-full supplier-orders-table bg-white rounded-xl divide-y divide-purple-200 text-base min-w-[1200px]">
                        <thead class="bg-gradient-to-r from-purple-200 via-purple-100 to-blue-100 sticky top-0 z-10">
                            <tr>
                                <th class="px-5 py-4 text-left font-black text-purple-800 text-lg uppercase tracking-wider border-l-4 border-purple-600 whitespace-nowrap">Order ID</th>
                                <th class="px-5 py-4 text-left font-black text-purple-800 text-lg uppercase tracking-wider whitespace-nowrap">Product</th>
                                <th class="px-5 py-4 text-left font-black text-purple-800 text-lg uppercase tracking-wider whitespace-nowrap">Quantity</th>
                                <th class="px-5 py-4 text-left font-black text-purple-800 text-lg uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="px-5 py-4 text-left font-black text-purple-800 text-lg uppercase tracking-wider whitespace-nowrap">Total</th>
                                <th class="px-5 py-4 text-left font-black text-purple-800 text-lg uppercase tracking-wider whitespace-nowrap">Placed At</th>
                            </tr>
                        </thead>
                        <tbody id="supplierOrdersTableBody" class="divide-y divide-purple-50">
                            @forelse($supplierOrders as $order)
                            <tr class="hover:bg-purple-100 hover:shadow-lg cursor-pointer transition group">
                                <td class="px-5 py-3 font-extrabold text-purple-900 whitespace-nowrap flex items-center gap-2 border-l-4 border-purple-600 bg-purple-50 text-lg"> <!-- Order ID -->
                                    <span>{{ is_object($order) ? $order->id : $order }}</span>
                                    <button class="ml-1 text-purple-400 hover:text-purple-700 focus:outline-none relative" data-tooltip="Copy" onclick="event.stopPropagation(); copyOrderId('{{ is_object($order) ? $order->id : $order }}', this)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2"/><rect x="3" y="3" width="13" height="13" rx="2"/></svg>
                                        <span class="absolute -top-6 left-1/2 -translate-x-1/2 bg-purple-700 text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition pointer-events-none" x-text="$el.getAttribute('data-tooltip') || 'Copy'"></span>
                                    </button>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-purple-800 font-medium">
                                    <span class="inline-block bg-purple-100 text-purple-800 rounded-full px-3 py-1 text-xs font-semibold">
                                        {{ (is_object($order) && is_object($order->product)) ? $order->product->name : '' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-purple-900 whitespace-nowrap font-semibold">{{ is_object($order) ? $order->quantity : '' }}</td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    @if(is_object($order))
                                        <span class="status-badge {{ strtolower($order->status) }} @if($order->status === 'pending') animate-pulse @endif font-bold text-base">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-purple-900 whitespace-nowrap font-extrabold text-lg"> <!-- Total -->
                                    @if(is_object($order) && is_object($order->product) && $order->product->unit_price !== null)
                                        ₦{{ number_format($order->quantity * $order->product->unit_price, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-purple-900 whitespace-nowrap font-medium">{{ (is_object($order) && $order->created_at) ? $order->created_at->format('n/j/Y, g:i A') : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 py-4">No supplier orders at the moment.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Placeholder modal for order details -->
                <div x-data="{ show: false, orderId: null }" x-init="window.addEventListener('show-order-modal', e => { show = true; orderId = e.detail.id })" x-show="show" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
                    <div class="bg-white rounded-xl shadow-xl p-10 max-w-md w-full relative">
                        <button class="absolute top-2 right-2 text-gray-400 hover:text-purple-700 text-2xl font-bold" @click="show = false">&times;</button>
                        <h3 class="text-2xl font-extrabold mb-2 text-purple-700">Order Details</h3>
                        <div class="h-1 w-12 bg-purple-500 rounded mb-4"></div>
                        <p class="mb-4 text-lg">Order ID: <span class="font-mono font-bold text-purple-700" x-text="orderId"></span></p>
                        <p class="text-gray-500">(Order details modal placeholder. Implement real details as needed.)</p>
            </div>
        </div>
        </div>
    </div>
    </div>
    <footer class="mt-12 text-center text-gray-500 text-base py-6">
        &copy; {{ date('Y') }} Bimbo Bakery Management. All rights reserved.
    </footer>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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

    // Copy to clipboard for Order ID
    function copyOrderId(orderId, el) {
        navigator.clipboard.writeText(orderId).then(function() {
            el.setAttribute('data-tooltip', 'Copied!');
            setTimeout(() => el.setAttribute('data-tooltip', 'Copy'), 1200);
        });
    }
    // Show scroll hint if table is scrollable
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.scroll-hint-table').forEach(function(wrapper) {
            const table = wrapper.querySelector('table');
            if (table && table.offsetWidth > wrapper.offsetWidth) {
                wrapper.setAttribute('data-scroll-hint', 'true');
            }
        });
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