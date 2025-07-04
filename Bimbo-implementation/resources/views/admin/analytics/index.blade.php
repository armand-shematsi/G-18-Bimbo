@extends('layouts.dashboard')

@section('header')
    Analytics Dashboard
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-semibold mb-4">Customer Segment Analytics</h3>
                <div class="mb-8">
                    <canvas id="segmentDistributionChart" width="400" height="200"></canvas>
                </div>
                <div>
                    <canvas id="avgSpendingChart" width="400" height="200"></canvas>
                </div>
                <h3 class="text-lg font-semibold mb-4">Top 5 Customers by Spending</h3>
                <div class="mb-8">
                    <table class="min-w-full bg-white border border-gray-200 mb-4">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border-b">Name</th>
                                <th class="px-4 py-2 border-b">Avg Spending</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topCustomers as $customer)
                                <tr>
                                    <td class="px-4 py-2 border-b">{{ $customer->Name }}</td>
                                    <td class="px-4 py-2 border-b">{{ number_format($customer->AvgSpending, 2) }}</td>
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
                <div>
                    <canvas id="avgPurchaseFrequencyChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Segment Distribution
    const segmentData = @json($segmentCounts);
    const segmentLabels = segmentData.map(s => 'Segment ' + s.Segment);
    const segmentCounts = segmentData.map(s => s.count);

    new Chart(document.getElementById('segmentDistributionChart'), {
        type: 'bar',
        data: {
            labels: segmentLabels,
            datasets: [{
                label: 'Number of Customers',
                data: segmentCounts,
                backgroundColor: ['#38bdf8', '#0ea5e9', '#facc15'],
            }]
        }
    });

    // Average Spending per Segment
    const avgSpendingData = @json($avgSpending);
    const avgSpendingLabels = avgSpendingData.map(s => 'Segment ' + s.Segment);
    const avgSpendingValues = avgSpendingData.map(s => s.avg_spending);

    new Chart(document.getElementById('avgSpendingChart'), {
        type: 'bar',
        data: {
            labels: avgSpendingLabels,
            datasets: [{
                label: 'Average Spending',
                data: avgSpendingValues,
                backgroundColor: ['#34d399', '#fbbf24', '#f87171'],
            }]
        }
    });

    // Bread Type Distribution
    const breadTypeData = @json($breadTypeDistribution);
    const breadTypeLabels = breadTypeData.map(b => b.PreferredBreadType);
    const breadTypeCounts = breadTypeData.map(b => b.count);
    new Chart(document.getElementById('breadTypeChart'), {
        type: 'bar',
        data: {
            labels: breadTypeLabels,
            datasets: [{
                label: 'Number of Customers',
                data: breadTypeCounts,
                backgroundColor: '#818cf8',
            }]
        }
    });

    // Location Distribution
    const locationData = @json($locationDistribution);
    const locationLabels = locationData.map(l => l.Location);
    const locationCounts = locationData.map(l => l.count);
    new Chart(document.getElementById('locationChart'), {
        type: 'bar',
        data: {
            labels: locationLabels,
            datasets: [{
                label: 'Number of Customers',
                data: locationCounts,
                backgroundColor: '#f472b6',
            }]
        }
    });

    // Average Purchase Frequency by Segment
    const avgPurchaseFrequencyData = @json($avgPurchaseFrequency);
    const avgPurchaseFrequencyLabels = avgPurchaseFrequencyData.map(s => 'Segment ' + s.Segment);
    const avgPurchaseFrequencyValues = avgPurchaseFrequencyData.map(s => s.avg_frequency);
    new Chart(document.getElementById('avgPurchaseFrequencyChart'), {
        type: 'bar',
        data: {
            labels: avgPurchaseFrequencyLabels,
            datasets: [{
                label: 'Avg Purchase Frequency',
                data: avgPurchaseFrequencyValues,
                backgroundColor: '#60a5fa',
            }]
        }
    });
</script>
@endpush
