@extends('layouts.supplier')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inventory Dashboard') }}
        </h2>
        <a href="{{ route('supplier.inventory.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            View Full Inventory
        </a>
    </div>
@endsection

@section('content')
    <h1>Supplier Inventory Dashboard</h1>

    <!-- Low Stock Alert Banner for Supplier -->
    @if(isset($lowStockItems) && $lowStockItems->count() > 0)
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
            <div class="font-bold mb-2">Low Stock Alert!</div>
            <ul class="list-disc pl-6">
                @foreach($lowStockItems as $item)
                    <li>
                        <span class="font-semibold">{{ $item->item_name }}</span> ({{ $item->quantity }} {{ $item->unit }}) is at or below its reorder level ({{ $item->reorder_level }} {{ $item->unit }})
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <!-- Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded shadow">
                    <div class="text-sm font-medium text-gray-600">Total Stock In</div>
                    <div class="text-2xl font-bold text-blue-900">{{ $totalStockIn }}</div>
                </div>
                <div class="bg-white p-4 rounded shadow">
                    <div class="text-sm font-medium text-gray-600">Total Stock Out</div>
                    <div class="text-2xl font-bold text-red-900">{{ $totalStockOut }}</div>
                </div>
                <div class="bg-white p-4 rounded shadow">
                    <div class="text-sm font-medium text-gray-600">Current Stock Items</div>
                    <div class="text-2xl font-bold text-green-900">{{ $stats['total'] ?? 0 }}</div>
                </div>
                <div class="bg-white p-4 rounded shadow">
                    <div class="text-sm font-medium text-yellow-600">Low Stock Alerts</div>
                    <div class="text-2xl font-bold text-yellow-900">{{ $stats['low_stock'] ?? 0 }}</div>
                </div>
                <div class="bg-white p-4 rounded shadow">
                    <div class="text-sm font-medium text-red-600">Out of Stock</div>
                    <div class="text-2xl font-bold text-red-900">{{ $stats['out_of_stock'] ?? 0 }}</div>
                </div>
                <div class="bg-white p-4 rounded shadow">
                    <div class="text-sm font-medium text-indigo-600">Over Stock (Above Reorder Level)</div>
                    <div class="text-2xl font-bold text-indigo-900">{{ $stats['over_stock'] ?? 0 }}</div>
                </div>
            </div>

            <!-- Chart.js Pie Chart -->
            <div class="mb-8 flex justify-center">
                <canvas id="statusChart" width="300" height="300" style="max-width:300px;max-height:300px;"></canvas>
            </div>

            <!-- Export Buttons -->
            <div class="mb-6 flex space-x-4">
                <form method="POST" action="#" onsubmit="exportCSV(event)">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Export CSV</button>
                </form>
                <form method="POST" action="#" onsubmit="exportPDF(event)">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Export PDF</button>
                </form>
            </div>

            <!-- Recent Activity Log -->
            <!-- Removed: Recent Activity section referencing $recentActivity -->
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-2">Daily Stock Movement (Last 7 Days)</h3>
            <canvas id="movementChart"></canvas>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-2">Top Selling Items</h3>
            <ul>
                @foreach($topSelling as $item)
                    <li class="flex justify-between border-b py-1">
                        <span>{{ $item->product_name }}</span>
                        <span class="font-bold">{{ $item->total_sold }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="bg-white p-4 rounded shadow mb-8">
        <h3 class="text-lg font-semibold mb-2">Current Stock Levels</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($inventory as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->item_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->unit }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->status === 'available')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">In Stock</span>
                            @elseif($item->status === 'low_stock')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Low Stock</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Out of Stock</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Analytics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-5">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Total Inventory Value</h3>
            <div class="text-2xl font-bold text-green-700">₦{{ number_format($totalInventoryValue, 2) }}</div>
        </div>
        <div class="bg-white shadow rounded-lg p-5">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Stock Level per Item</h3>
            <canvas id="stockLevelBarChart" height="120"></canvas>
        </div>
    </div>
    <!-- End Analytics Cards -->

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Available', 'Low Stock', 'Out of Stock'],
                datasets: [{
                    data: [{{ $stats['available'] ?? 0 }}, {{ $stats['low_stock'] ?? 0 }}, {{ $stats['out_of_stock'] ?? 0 }}],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(251, 191, 36, 0.7)',
                        'rgba(239, 68, 68, 0.7)'
                    ],
                    borderColor: [
                        'rgba(16, 185, 129, 1)',
                        'rgba(251, 191, 36, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                },
            },
        });

        const ctxMovement = document.getElementById('movementChart').getContext('2d');
        const movementChart = new Chart(ctxMovement, {
            type: 'line',
            data: {
                labels: {!! json_encode($dates) !!},
                datasets: [
                    {
                        label: 'Stock In',
                        data: {!! json_encode($stockInData) !!},
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        fill: true,
                    },
                    {
                        label: 'Stock Out',
                        data: {!! json_encode($stockOutData) !!},
                        borderColor: 'rgba(239, 68, 68, 1)',
                        backgroundColor: 'rgba(239, 68, 68, 0.2)',
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Stock Movement Trends'
                    }
                }
            }
        });

        // Stock Level Bar Chart
        new Chart(document.getElementById('stockLevelBarChart'), {
            type: 'bar',
            data: {
                labels: {!! $stockLevelChartData->pluck('item_name')->toJson() !!},
                datasets: [{
                    label: 'Quantity',
                    data: {!! $stockLevelChartData->pluck('quantity')->toJson() !!},
                    backgroundColor: '#60a5fa',
                }]
            },
            options: {responsive: true, indexAxis: 'y'}
        });

        // Export CSV (client-side)
        function exportCSV(event) {
            event.preventDefault();
            let csv = 'Item Name,Item Type,Quantity,Unit,Status,Reorder Level\n';
            @foreach($inventory as $item)
                csv += `"{{ $item->item_name }}","{{ $item->item_type }}",{{ $item->quantity }},"{{ $item->unit }}","{{ $item->status }}",{{ $item->reorder_level }}\n`;
            @endforeach
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.setAttribute('hidden', '');
            a.setAttribute('href', url);
            a.setAttribute('download', 'inventory.csv');
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        // Export PDF (client-side, simple print)
        function exportPDF(event) {
            event.preventDefault();
            window.print();
        }
    </script>
@endsection
