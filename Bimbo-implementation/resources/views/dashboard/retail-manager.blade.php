@extends('layouts.retail-manager')

@section('header')
Retail Manager Dashboard
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-100 to-green-200 py-10">
    <div class="max-w-7xl mx-auto">
        <!-- Place Order Form -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-blue-700 mb-4">Place New Order</h2>
            <form method="POST" action="{{ route('retail.orders.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Product</label>
                    <select name="product_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="">Select product</option>
                        @foreach(\App\Models\Product::all() as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Quantity</label>
                    <input type="number" name="quantity" class="w-full border border-gray-300 rounded-lg px-3 py-2" min="1" required>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Place Order</button>
            </form>
        </div>
        <!-- Order History Table -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-green-700 mb-4">My Orders</h2>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Placed At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach(\App\Models\RetailerOrder::where('retailer_id', auth()->id())->orderBy('created_at', 'desc')->get() as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->product->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $order->status === 'received' ? 'text-green-700 font-bold' : 'text-yellow-700 font-bold' }}">{{ ucfirst($order->status) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Current Inventory -->
            <div class="bg-gradient-to-br from-green-400 to-blue-500 shadow-xl rounded-2xl p-6 flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-lg font-bold text-white">Supplier Inventory</dt>
                        <dd class="text-2xl font-extrabold text-white drop-shadow">{{ $supplierInventory->count() }} Items</dd>
                    </dl>
                </div>
            </div>
            <!-- Pending Orders -->
            <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 shadow-xl rounded-2xl p-6 flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-lg font-bold text-white">Pending Orders</dt>
                        <dd class="text-2xl font-extrabold text-white drop-shadow">0</dd>
                    </dl>
                </div>
            </div>
            <!-- Low Stock Items -->
            <div class="bg-gradient-to-br from-red-400 to-pink-500 shadow-xl rounded-2xl p-6 flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-lg font-bold text-white">Low Stock Items</dt>
                        <dd class="text-2xl font-extrabold text-white drop-shadow">{{ $lowStockItems->count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <!-- Supplier Inventory Table -->
        <div class="mt-8">
            <h3 class="text-2xl font-extrabold text-blue-800 mb-4">Supplier Inventory</h3>
            <div class="mt-4 bg-white shadow-lg rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-blue-200">
                        <thead class="bg-gradient-to-r from-blue-700 to-green-600 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Item Name</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Supplier</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Reorder Level</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-blue-100">
                            @forelse($supplierInventory as $item)
                            <tr class="hover:bg-blue-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-900">{{ $item->item_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-800 font-semibold">{{ $item->user->name ?? 'Unknown' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-900">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-900">{{ $item->unit }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $item->status === 'available' ? 'bg-green-100 text-green-800' : 
                                           ($item->status === 'low_stock' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                    </span>
                                    @if($item->needsReorder())
                                    <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Reorder Needed
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-900">{{ $item->reorder_level }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No supplier inventory found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Quick Actions -->
        <div class="mt-8">
            <h3 class="text-2xl font-extrabold text-green-700 mb-4">Quick Actions</h3>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <a href="{{ route('retail.orders.create') }}" class="relative block w-full p-6 bg-gradient-to-br from-green-400 to-blue-400 border-2 border-green-500 rounded-2xl shadow-lg hover:scale-105 hover:shadow-2xl transition-all duration-200">
                    <h4 class="text-xl font-bold text-white">Place Order</h4>
                    <p class="mt-1 text-base text-white/90">Order new bread products</p>
                </a>
                <a href="{{ route('retail.inventory.check') }}" class="relative block w-full p-6 bg-gradient-to-br from-blue-400 to-green-400 border-2 border-blue-500 rounded-2xl shadow-lg hover:scale-105 hover:shadow-2xl transition-all duration-200">
                    <h4 class="text-xl font-bold text-white">Check Inventory</h4>
                    <p class="mt-1 text-base text-white/90">View current stock levels</p>
                </a>
                <a href="{{ route('retail.forecast.index') }}" class="relative block w-full p-6 bg-gradient-to-br from-yellow-400 to-pink-400 border-2 border-yellow-500 rounded-2xl shadow-lg hover:scale-105 hover:shadow-2xl transition-all duration-200">
                    <h4 class="text-xl font-bold text-white">View Forecast</h4>
                    <p class="mt-1 text-base text-white/90">Check demand predictions</p>
                </a>
            </div>
        </div>
        <!-- Order Analytics Charts -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-gradient-to-br from-blue-100 to-green-100 p-8 rounded-2xl shadow-xl">
                <h3 class="text-xl font-bold text-blue-800 mb-4">Orders per Day (Last 7 Days)</h3>
                <canvas id="ordersPerDayChart" height="120"></canvas>
            </div>
            <div class="bg-gradient-to-br from-yellow-100 to-pink-100 p-8 rounded-2xl shadow-xl">
                <h3 class="text-xl font-bold text-yellow-700 mb-4">Order Status Breakdown</h3>
                <canvas id="orderStatusChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.orderDays = @json($orderDays);
    window.orderCounts = @json($orderCounts);
    window.statusCounts = @json($statusCounts);
</script>
<script src="{{ asset('js/retail-manager-charts.js') }}"></script>
@endpush
@endsection