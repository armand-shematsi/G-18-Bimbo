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

                <h3 class="text-lg font-semibold mb-4">Customer Spending Distribution by Segment</h3>
                <p class="text-sm text-gray-600 mb-4">Visualizing spending patterns across {{ count($segmentRecommendations) }} customers</p>
                <div class="bg-yellow-50 p-4 rounded mb-4">
                    <p class="text-sm text-yellow-800">
                        <strong>Debug Info:</strong>
                        Segment Recommendations Count: {{ count($segmentRecommendations) }} |
                        Customer Segments Count: {{ count($customerSegments) }} |
                        Bread Type Distribution Count: {{ count($breadTypeDistribution) }}
                    </p>
                </div>
                <div class="mb-8">
                    <canvas id="segmentDistributionChart" width="400" height="200"></canvas>
                </div>

                <h3 class="text-lg font-semibold mb-4">Average Spending by Segment</h3>
                <div class="mb-8">
                    <canvas id="avgSpendingChart" width="400" height="200"></canvas>
                </div>

                <h3 class="text-lg font-semibold mb-4">Top Customers by Spending</h3>
                <div class="mb-8">
                    <canvas id="customerSpendingChart" width="800" height="400"></canvas>
                    <div id="chartDebug" class="text-sm text-gray-600 mt-2"></div>
                    <div id="dataDebug" class="text-sm text-gray-600 mt-2"></div>
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

            // Simple Customer Spending Chart
    console.log('Creating simple customer spending chart...');

    const customerData = @json($segmentRecommendations);
    console.log('Customer data received:', customerData ? customerData.length : 'No data');

    // Display debug info
    document.getElementById('dataDebug').innerHTML = 'Data received: ' + (customerData ? customerData.length : 0) + ' customers';

    const canvas = document.getElementById('customerSpendingChart');
    if (!canvas) {
        console.error('Canvas not found!');
        document.getElementById('chartDebug').innerHTML = 'Canvas element not found!';
        return;
    }

    // Simple data processing
    const segments = {};
    if (customerData && customerData.length > 0) {
        customerData.forEach(customer => {
            const segment = customer.segment || 0;
            if (!segments[segment]) {
                segments[segment] = { count: 0, totalSpending: 0 };
            }
            segments[segment].count++;
            segments[segment].totalSpending += customer.avg_spending || 0;
        });

        const labels = Object.keys(segments).map(s => 'Segment ' + s);
        const data = Object.values(segments).map(s => s.totalSpending / s.count);
        const counts = Object.values(segments).map(s => s.count);

        console.log('Chart data:', { labels, data, counts });

        try {
            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Average Spending per Customer',
                        data: data,
                        backgroundColor: ['#3b82f6', '#10b981'],
                        borderColor: ['#2563eb', '#059669'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Customer Spending by Segment'
                        },
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Average Spending (₦)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return '₦' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
            console.log('Chart created successfully!');
            document.getElementById('chartDebug').innerHTML = 'Chart created successfully!';
        } catch (error) {
            console.error('Chart error:', error);
            document.getElementById('chartDebug').innerHTML = 'Chart error: ' + error.message;
            canvas.innerHTML = '<div class="text-center text-red-500 py-8">Error: ' + error.message + '</div>';
        }
    } else {
        console.log('No customer data available');
        document.getElementById('chartDebug').innerHTML = 'No customer data available';
        canvas.innerHTML = '<div class="text-center text-gray-500 py-8">No customer data available</div>';
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
