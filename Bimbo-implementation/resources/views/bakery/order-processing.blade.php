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
<div class="max-w-7xl mx-auto py-8"
    data-supplier-order-route="{{ route('bakery.order-processing.supplier-order') }}"
    data-retailer-orders-route="{{ route('bakery.order-processing.retailer-orders') }}"
    data-receive-order-base-url="{{ url('bakery/order-processing/retailer-orders') }}">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Place Order to Supplier -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-blue-700 mb-4">Place Order to Supplier</h2>
            <form id="supplierOrderForm">
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
                        @forelse($retailerOrders as $order)
                        <tr>
                            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>{{ $order->retailer && $order->retailer->name ? $order->retailer->name : '-' }}</td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>{{ $order->product }}</td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>{{ $order->quantity }}</td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm {{ $order->status === 'received' ? 'text-green-700 font-bold' : 'text-yellow-700 font-bold' }}'>{{ ucfirst($order->status) }}</td>
                            <td>
                                @if($order->status === 'pending')
                                <button data-order-id="{{ $order->id }}" class='bg-green-500 text-white px-2 py-1 rounded text-xs mark-received-btn'>Mark Received</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-400">No retailer orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/order-processing.js') }}"></script>
@endpush