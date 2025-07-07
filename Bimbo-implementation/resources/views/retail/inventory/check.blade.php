@extends('layouts.retail-manager')

@section('header')
    Check Inventory
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Inventory Dashboard -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white shadow-lg rounded-xl p-6 flex items-center gap-4">
                <div class="bg-blue-100 text-blue-600 rounded-full p-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/></svg>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Total Bread in Stock</div>
                    <div class="text-2xl font-bold">{{ $totalBreadInStock }}</div>
                </div>
            </div>
            <div class="bg-white shadow-lg rounded-xl p-6 flex items-center gap-4">
                <div class="bg-green-100 text-green-600 rounded-full p-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 0 1 4-4h4"/></svg>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Today's Deliveries</div>
                    <div class="text-2xl font-bold">{{ $todaysDeliveries }}</div>
                </div>
            </div>
            <div class="bg-white shadow-lg rounded-xl p-6 flex items-center gap-4">
                <div class="bg-emerald-100 text-emerald-600 rounded-full p-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 0V4m0 16v-4"/></svg>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Today's Sales</div>
                    <div class="text-2xl font-bold">₦{{ number_format($todaysSales, 2) }}</div>
                </div>
            </div>
            <div class="bg-white shadow-lg rounded-xl p-6 flex items-center gap-4">
                <div class="bg-red-100 text-red-600 rounded-full p-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/></svg>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Reorder Alerts</div>
                    <div class="text-2xl font-bold text-red-600">{{ $reorderAlerts }}</div>
                </div>
            </div>
        </div>
        <!-- End Inventory Dashboard -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Search and Filter -->
                <div class="mb-8">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" id="inventory-search" placeholder="Search products..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" onkeyup="filterInventory()">
                        </div>
                        <div class="flex gap-4">
                            <select id="category-filter" class="rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" onchange="filterInventory()">
                                <option value="">All Categories</option>
                                <option value="bread">Bread</option>
                                <option value="pastries">Pastries</option>
                                <option value="cakes">Cakes</option>
                            </select>
                            <select id="status-filter" class="rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" onchange="filterInventory()">
                                <option value="">All Status</option>
                                <option value="in_stock">In Stock</option>
                                <option value="low_stock">Low Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                            </select>
                        </div>
                        <button onclick="exportInventory()" class="bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Export
                        </button>
                    </div>
                </div>

                <!-- Total Stock Value -->
                <div class="mb-4 text-lg font-bold text-blue-700">
                    Total Stock Value: ₦{{ number_format($inventory->sum(fn($item) => $item->quantity * ($item->unit_price ?? 0)), 2) }}
                </div>

                <!-- Inventory Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto max-h-96 overflow-y-auto sticky-table">
                        <table id="inventory-table" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0 z-10">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Level</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reorder Level</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($inventory as $item)
                                <tr class="{{ $item->needsReorder() ? 'bg-red-50' : ($item->quantity > 0 && $item->quantity <= $item->reorder_level ? 'bg-yellow-50' : '') }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->item_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->item_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="w-32 bg-gray-200 rounded-full h-3">
                                            <div class="h-3 rounded-full {{ $item->quantity == 0 ? 'bg-red-500' : ($item->quantity <= $item->reorder_level ? 'bg-yellow-400' : 'bg-green-500') }}" style="width: {{ min(100, ($item->quantity / max(1, $item->reorder_level * 2)) * 100) }}%"></div>
                                        </div>
                                        <span class="text-xs ml-2 font-bold {{ $item->quantity == 0 ? 'text-red-600' : ($item->quantity <= $item->reorder_level ? 'text-yellow-700' : 'text-green-700') }}">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->reorder_level }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->updated_at ? $item->updated_at->format('Y-m-d H:i') : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $item->quantity > $item->reorder_level ? 'bg-green-100 text-green-800' : ($item->quantity > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $item->quantity > $item->reorder_level ? 'In Stock' : ($item->quantity > 0 ? 'Low Stock' : 'Out of Stock') }}
                                        </span>
                                        @if($item->needsReorder())
                                        <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">
                                            Reorder Needed
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button class="text-blue-600 hover:text-blue-800">Update</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No inventory found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Inventory Trends Chart -->
                <div class="mt-8 bg-white rounded-2xl shadow-lg p-6">
                    <h4 class="text-lg font-bold text-blue-700 mb-2">Inventory Trends (Top 7 Items)</h4>
                    <canvas id="inventoryTrendsChart" height="100"></canvas>
                </div>

                <!-- Pagination -->
                <div class="mt-4 flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Showing 1 to 2 of 24 results
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 rounded-md border border-gray-300 text-sm text-gray-500 hover:bg-gray-50">Previous</button>
                        <button class="px-3 py-1 rounded-md border border-gray-300 text-sm text-gray-500 hover:bg-gray-50">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Inventory Trends Data (simulate for now)
    window.inventoryTrends = @json($inventory->map(fn($item) => $item->quantity)->take(7));
    window.inventoryLabels = @json($inventory->map(fn($item) => $item->item_name)->take(7));

    // Inventory Trends Chart
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('inventoryTrendsChart')) {
            new Chart(document.getElementById('inventoryTrendsChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: window.inventoryLabels,
                    datasets: [{
                        label: 'Stock Level',
                        data: window.inventoryTrends,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }
    });
    // Inventory Search/Filter
    window.filterInventory = function() {
        const input = document.getElementById('inventory-search').value.toLowerCase();
        const category = document.getElementById('category-filter').value;
        const status = document.getElementById('status-filter').value;
        const rows = document.querySelectorAll('#inventory-table tbody tr');
        rows.forEach(row => {
            let show = true;
            if (input && !row.innerText.toLowerCase().includes(input)) show = false;
            if (category && !row.children[1].innerText.toLowerCase().includes(category)) show = false;
            if (status) {
                const stat = row.children[6].innerText.trim().toLowerCase();
                if (status === 'in_stock' && stat !== 'in stock') show = false;
                if (status === 'low_stock' && stat !== 'low stock') show = false;
                if (status === 'out_of_stock' && stat !== 'out of stock') show = false;
            }
            row.style.display = show ? '' : 'none';
        });
    };
    // Export Inventory to CSV
    window.exportInventory = function() {
        let csv = 'Product,Category,Current Stock,Stock Level,Reorder Level,Last Updated,Status\n';
        document.querySelectorAll('#inventory-table tbody tr').forEach(row => {
            if (row.style.display !== 'none') {
                const cols = row.querySelectorAll('td');
                csv += Array.from(cols).slice(0, 7).map(td => '"' + td.innerText.replace(/"/g, '""') + '"').join(',') + '\n';
            }
        });
        const blob = new Blob([csv], { type: 'text/csv' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'retail_inventory.csv';
        link.click();
    };
</script>
@endpush
