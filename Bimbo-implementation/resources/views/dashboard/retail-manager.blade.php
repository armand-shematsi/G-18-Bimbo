@extends('layouts.retail-manager')

@section('header')
    Retail Manager Dashboard
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-100 to-green-200 py-10">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Current Inventory -->
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 shadow-xl rounded-2xl p-6 flex items-center">
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
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 shadow-xl rounded-2xl p-6 flex items-center">
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
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 shadow-xl rounded-2xl p-6 flex items-center">
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
        <!-- Removed supplier inventory table as requested -->
        <!--
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
        -->
        <!-- Quick Actions -->
        <div class="mt-8">
            <h3 class="text-2xl font-extrabold text-green-700 mb-4">Quick Actions</h3>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('retail.orders.create') }}" class="relative block w-full p-6 bg-gradient-to-br from-blue-500 to-indigo-600 border-2 border-blue-600 rounded-2xl shadow-lg hover:scale-105 hover:shadow-2xl transition-all duration-200">
                    <h4 class="text-xl font-bold text-white">Place Order</h4>
                    <p class="mt-1 text-base text-white/90">Order new bread products</p>
                </a>
                <a href="{{ route('retail.inventory.check') }}" class="relative block w-full p-6 bg-gradient-to-br from-blue-500 to-indigo-600 border-2 border-blue-600 rounded-2xl shadow-lg hover:scale-105 hover:shadow-2xl transition-all duration-200">
                    <h4 class="text-xl font-bold text-white">Check Inventory</h4>
                    <p class="mt-1 text-base text-white/90">View current stock levels</p>
                </a>
                <a href="{{ route('retail.forecast.index') }}" class="relative block w-full p-6 bg-gradient-to-br from-blue-500 to-indigo-600 border-2 border-blue-600 rounded-2xl shadow-lg hover:scale-105 hover:shadow-2xl transition-all duration-200">
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
        <!-- Reports Button at the bottom -->
        <div class="w-full flex justify-end mt-8">
            <a href="{{ route('reports.downloads') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg text-xl transition-all duration-200">
                Reports
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Orders per Day Chart
    const ordersPerDayCtx = document.getElementById('ordersPerDayChart').getContext('2d');
    new Chart(ordersPerDayCtx, {
        type: 'bar',
        data: {
            labels: @json($orderDays),
            datasets: [{
                label: 'Orders',
                data: @json($orderCounts),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Order Status Breakdown Chart
    const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(orderStatusCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(@json($statusCounts)),
            datasets: [{
                label: 'Orders',
                data: Object.values(@json($statusCounts)),
                backgroundColor: [
                    'rgba(255, 205, 86, 0.7)', // pending
                    'rgba(54, 162, 235, 0.7)', // processing
                    'rgba(153, 102, 255, 0.7)', // shipped (purple)
                    'rgba(75, 192, 192, 0.7)', // completed
                    'rgba(255, 99, 132, 0.7)'  // cancelled
                ],
                borderColor: [
                    'rgba(255, 205, 86, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endpush
@endsection
