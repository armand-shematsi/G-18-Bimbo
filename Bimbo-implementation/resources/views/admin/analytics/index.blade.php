@extends('layouts.dashboard')

@section('header')
    Analytics Dashboard
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6 flex space-x-4">
                    <a href="{{ route('admin.analytics') }}" class="px-4 py-2 rounded {{ request()->routeIs('admin.analytics') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">Customer Segmentation</a>
                    <a href="{{ route('admin.analytics.sales_predictions') }}" class="px-4 py-2 rounded {{ request()->routeIs('admin.analytics.sales_predictions') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">Sales Predictions</a>
                </div>

                <h3 class="text-lg font-semibold mb-4">Customer Segment Distribution (ML-Generated)</h3>
                <div class="mb-8">
                    <canvas id="segmentDistributionChart" width="400" height="200"></canvas>
                </div>

                <h3 class="text-lg font-semibold mb-4">Average Spending by Segment</h3>
                <div class="mb-8">
                    <canvas id="avgSpendingChart" width="400" height="200"></canvas>
                </div>

                <h3 class="text-lg font-semibold mb-4">Top Customers by Spending</h3>
                <div class="mb-8">
                    <table class="min-w-full bg-white border border-gray-200 mb-4">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border-b">Name</th>
                                <th class="px-4 py-2 border-b">Segment</th>
                                <th class="px-4 py-2 border-b">Avg Spending</th>
                                <th class="px-4 py-2 border-b">Purchase Frequency</th>
                                <th class="px-4 py-2 border-b">Preferred Bread</th>
                                <th class="px-4 py-2 border-b">Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(array_slice($segmentRecommendations, 0, 10) as $customer)
                                <tr>
                                    <td class="px-4 py-2 border-b">{{ $customer['name'] }}</td>
                                    <td class="px-4 py-2 border-b">Segment {{ $customer['segment'] }}</td>
                                    <td class="px-4 py-2 border-b">₦{{ number_format($customer['avg_spending'], 2) }}</td>
                                    <td class="px-4 py-2 border-b">{{ $customer['purchase_frequency'] }}</td>
                                    <td class="px-4 py-2 border-b">{{ $customer['preferred_bread_type'] }}</td>
                                    <td class="px-4 py-2 border-b">{{ $customer['location'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <h3 class="text-lg font-semibold mb-4">Bread Type Distribution</h3>
                <div class="mb-8">
                    <canvas id="breadTypeChart" width="400" height="200"></canvas>
                </div>

                <h3 class="text-lg font-semibold mb-4">Customer Location Distribution</h3>
                <div class="mb-8">
                    <canvas id="locationChart" width="400" height="200"></canvas>
                </div>

                <h3 class="text-lg font-semibold mb-4">Average Purchase Frequency by Segment</h3>
                <div class="mb-8">
                    <canvas id="avgPurchaseFrequencyChart" width="400" height="200"></canvas>
                </div>

                <h3 class="text-lg font-semibold mb-4">ML Recommendations by Segment</h3>
                <div class="mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($customerSegments as $segment => $count)
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-blue-800">Segment {{ $segment }}</h4>
                                <p class="text-sm text-blue-600">{{ $count }} customers</p>
                                @if($segment == 'high_value')
                                    <p class="text-xs text-gray-600 mt-2">Recommend: Loyalty rewards and exclusive previews</p>
                                @elseif($segment == 'medium_value')
                                    <p class="text-xs text-gray-600 mt-2">Recommend: Bundle offers and personalized discounts</p>
                                @else
                                    <p class="text-xs text-gray-600 mt-2">Recommend: Win-back campaigns and introductory offers</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Debug: Log data to console
    console.log('Customer Segments Data:', @json($customerSegments));
    console.log('Segment Recommendations Data:', @json($segmentRecommendations));

    // Segment Distribution
    const segmentData = @json($customerSegments);
    const segmentLabels = Object.keys(segmentData).map(s => s.replace('_', ' ').toUpperCase());
    const segmentCounts = Object.values(segmentData);

    if (segmentCounts.length > 0 && segmentCounts.some(count => count > 0)) {
        new Chart(document.getElementById('segmentDistributionChart'), {
            type: 'bar',
            data: {
                labels: segmentLabels,
                datasets: [{
                    label: 'Number of Customers',
                    data: segmentCounts,
                    backgroundColor: ['#38bdf8', '#0ea5e9', '#facc15'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    } else {
        document.getElementById('segmentDistributionChart').innerHTML = '<div class="text-center text-gray-500 py-8">No customer segment data available</div>';
    }

    // Average Spending by Segment
    const avgSpendingData = @json($segmentRecommendations);
    const segmentSpending = {};
    avgSpendingData.forEach(customer => {
        if (!segmentSpending[customer.segment]) {
            segmentSpending[customer.segment] = { total: 0, count: 0 };
        }
        segmentSpending[customer.segment].total += customer.avg_spending;
        segmentSpending[customer.segment].count++;
    });

    const avgSpendingLabels = Object.keys(segmentSpending).map(s => 'Segment ' + s);
    const avgSpendingValues = Object.values(segmentSpending).map(s => s.total / s.count);

    if (avgSpendingValues.length > 0) {
        new Chart(document.getElementById('avgSpendingChart'), {
            type: 'bar',
            data: {
                labels: avgSpendingLabels,
                datasets: [{
                    label: 'Average Spending',
                    data: avgSpendingValues,
                    backgroundColor: ['#34d399', '#fbbf24', '#f87171'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₦' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    } else {
        document.getElementById('avgSpendingChart').innerHTML = '<div class="text-center text-gray-500 py-8">No spending data available</div>';
    }

    // Bread Type Distribution
    const breadTypeData = @json($breadTypeDistribution);
    const breadTypeLabels = Object.keys(breadTypeData);
    const breadTypeCounts = Object.values(breadTypeData);

    if (breadTypeCounts.length > 0 && breadTypeCounts.some(count => count > 0)) {
        new Chart(document.getElementById('breadTypeChart'), {
            type: 'bar',
            data: {
                labels: breadTypeLabels,
                datasets: [{
                    label: 'Number of Customers',
                    data: breadTypeCounts,
                    backgroundColor: '#818cf8',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    } else {
        document.getElementById('breadTypeChart').innerHTML = '<div class="text-center text-gray-500 py-8">No bread type data available</div>';
    }

    // Location Distribution
    const locationData = @json($locationDistribution);
    const locationLabels = Object.keys(locationData);
    const locationCounts = Object.values(locationData);

    if (locationCounts.length > 0 && locationCounts.some(count => count > 0)) {
        new Chart(document.getElementById('locationChart'), {
            type: 'bar',
            data: {
                labels: locationLabels,
                datasets: [{
                    label: 'Number of Customers',
                    data: locationCounts,
                    backgroundColor: '#f472b6',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    } else {
        document.getElementById('locationChart').innerHTML = '<div class="text-center text-gray-500 py-8">No location data available</div>';
    }

    // Average Purchase Frequency by Segment
    const avgPurchaseFrequencyData = @json($avgPurchaseFrequency);
    const avgPurchaseFrequencyLabels = Object.keys(avgPurchaseFrequencyData).map(s => 'Segment ' + s);
    const avgPurchaseFrequencyValues = Object.values(avgPurchaseFrequencyData);

    if (avgPurchaseFrequencyValues.length > 0) {
        new Chart(document.getElementById('avgPurchaseFrequencyChart'), {
            type: 'bar',
            data: {
                labels: avgPurchaseFrequencyLabels,
                datasets: [{
                    label: 'Avg Purchase Frequency',
                    data: avgPurchaseFrequencyValues,
                    backgroundColor: '#60a5fa',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toFixed(1);
                            }
                        }
                    }
                }
            }
        });
    } else {
        document.getElementById('avgPurchaseFrequencyChart').innerHTML = '<div class="text-center text-gray-500 py-8">No purchase frequency data available</div>';
    }
</script>
@endpush
