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
</script>
@endpush
