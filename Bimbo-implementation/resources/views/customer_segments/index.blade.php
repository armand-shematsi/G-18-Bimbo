@extends('layouts.dashboard')

@section('header')
    Customer Segments Analytics
@endsection

@section('content')

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Total Customers</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $analyticsData['totalCustomers'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Total Segments</h3>
            <p class="text-3xl font-bold text-green-600">{{ $analyticsData['totalSegments'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Avg Spending</h3>
            <p class="text-3xl font-bold text-purple-600">${{ number_format($analyticsData['avgSpending'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Avg Frequency</h3>
            <p class="text-3xl font-bold text-orange-600">{{ number_format($analyticsData['avgFrequency'], 1) }}</p>
        </div>
    </div>

    <!-- Analytics Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Segment Distribution Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Customer Segment Distribution</h3>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="segmentChart"></canvas>
            </div>
        </div>

        <!-- Average Spending by Segment -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Average Spending by Segment</h3>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="spendingChart"></canvas>
            </div>
        </div>

        <!-- Purchase Frequency Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Purchase Frequency by Segment</h3>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="frequencyChart"></canvas>
            </div>
        </div>

        <!-- Location Distribution -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Customer Location Distribution</h3>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="locationChart"></canvas>
            </div>
        </div>

        <!-- Bread Type Preferences -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Bread Type Preferences</h3>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="breadChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Customer Segments Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900">Customer Segments Data</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">CustomerID</th>
                        <th class="px-4 py-2 border-b">Name</th>
                        <th class="px-4 py-2 border-b">Segment</th>
                        <th class="px-4 py-2 border-b">PurchaseFrequency</th>
                        <th class="px-4 py-2 border-b">AvgSpending</th>
                        <th class="px-4 py-2 border-b">Location</th>
                        <th class="px-4 py-2 border-b">PreferredBreadType</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($segments as $segment)
                        <tr>
                            <td class="px-4 py-2 border-b">{{ $segment->CustomerID }}</td>
                            <td class="px-4 py-2 border-b">{{ $segment->Name }}</td>
                            <td class="px-4 py-2 border-b">{{ $segment->Segment }}</td>
                            <td class="px-4 py-2 border-b">{{ $segment->PurchaseFrequency }}</td>
                            <td class="px-4 py-2 border-b">${{ number_format($segment->AvgSpending, 2) }}</td>
                            <td class="px-4 py-2 border-b">{{ $segment->Location }}</td>
                            <td class="px-4 py-2 border-b">{{ $segment->PreferredBreadType }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 px-4 py-3">
                {{ $segments->links() }}
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prepare data from PHP
        const segmentLabels = @json($analyticsData['segmentDistribution']->pluck('Segment'));
        const segmentData = @json($analyticsData['segmentDistribution']->pluck('count'));
        const spendingLabels = @json($analyticsData['avgSpendingBySegment']->pluck('Segment'));
        const spendingData = @json($analyticsData['avgSpendingBySegment']->pluck('avg_spending'));
        const frequencyLabels = @json($analyticsData['frequencyBySegment']->pluck('Segment'));
        const frequencyData = @json($analyticsData['frequencyBySegment']->pluck('avg_frequency'));
        const locationLabels = @json($analyticsData['locationDistribution']->pluck('Location'));
        const locationData = @json($analyticsData['locationDistribution']->pluck('count'));
        const breadLabels = @json($analyticsData['breadPreferences']->pluck('PreferredBreadType'));
        const breadData = @json($analyticsData['breadPreferences']->pluck('count'));

        // Segment Distribution Chart (Pie Chart)
        const segmentCtx = document.getElementById('segmentChart').getContext('2d');
        new Chart(segmentCtx, {
            type: 'pie',
            data: {
                labels: segmentLabels,
                datasets: [{
                    data: segmentData,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                        '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Average Spending Chart (Bar Chart)
        const spendingCtx = document.getElementById('spendingChart').getContext('2d');
        new Chart(spendingCtx, {
            type: 'bar',
            data: {
                labels: spendingLabels,
                datasets: [{
                    label: 'Average Spending ($)',
                    data: spendingData,
                    backgroundColor: '#36A2EB',
                    borderColor: '#2693E6',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });

        // Purchase Frequency Chart (Line Chart)
        const frequencyCtx = document.getElementById('frequencyChart').getContext('2d');
        new Chart(frequencyCtx, {
            type: 'line',
            data: {
                labels: frequencyLabels,
                datasets: [{
                    label: 'Purchase Frequency',
                    data: frequencyData,
                    borderColor: '#FF6384',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Location Distribution Chart (Doughnut Chart)
        const locationCtx = document.getElementById('locationChart').getContext('2d');
        new Chart(locationCtx, {
            type: 'doughnut',
            data: {
                labels: locationLabels,
                datasets: [{
                    data: locationData,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Bread Preferences Chart (Bar Chart)
        const breadCtx = document.getElementById('breadChart').getContext('2d');
        new Chart(breadCtx, {
            type: 'bar',
            data: {
                labels: breadLabels,
                datasets: [{
                    label: 'Customer Count',
                    data: breadData,
                    backgroundColor: '#4BC0C0',
                    borderColor: '#3BA9A9',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
    </script>
@endsection
