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
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <!-- Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-blue-600">Total Items</div>
                    <div class="text-2xl font-bold text-blue-900">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-green-600">Available</div>
                    <div class="text-2xl font-bold text-green-900">{{ $stats['available'] }}</div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-yellow-600">Low Stock</div>
                    <div class="text-2xl font-bold text-yellow-900">{{ $stats['low_stock'] }}</div>
                </div>
                <div class="bg-red-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-red-600">Out of Stock</div>
                    <div class="text-2xl font-bold text-red-900">{{ $stats['out_of_stock'] }}</div>
                </div>
            </div>

            <!-- Chart.js Pie Chart -->
            <div class="mb-8">
                <canvas id="statusChart" width="400" height="200"></canvas>
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
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Recent Activity</h3>
                <ul class="divide-y divide-gray-200">
                    @forelse($recentActivity as $item)
                        <li class="py-2 flex justify-between items-center">
                            <span>
                                <strong>{{ $item->item_name }}</strong> ({{ $item->status }})
                                <span class="text-xs text-gray-500 ml-2">Updated: {{ $item->updated_at->format('Y-m-d H:i') }}</span>
                            </span>
                            <span class="text-xs text-gray-400">ID: {{ $item->id }}</span>
                        </li>
                    @empty
                        <li class="py-2 text-gray-500">No recent activity.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Available', 'Low Stock', 'Out of Stock'],
                datasets: [{
                    data: [{{ $stats['available'] }}, {{ $stats['low_stock'] }}, {{ $stats['out_of_stock'] }}],
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
