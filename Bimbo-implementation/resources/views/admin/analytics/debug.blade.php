@extends('layouts.dashboard')

@section('header')
    Analytics Debug
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-semibold mb-4">Raw Data Debug</h3>

                <h4 class="font-semibold mt-4">Sales Data:</h4>
                <pre class="bg-gray-100 p-4 rounded text-sm overflow-auto">{{ print_r($salesData ?? 'No sales data', true) }}</pre>

                <h4 class="font-semibold mt-4">Sales Trends:</h4>
                <pre class="bg-gray-100 p-4 rounded text-sm overflow-auto">{{ print_r($salesTrends ?? 'No trends data', true) }}</pre>

                <h4 class="font-semibold mt-4">Demand Forecast:</h4>
                <pre class="bg-gray-100 p-4 rounded text-sm overflow-auto">{{ print_r($demandForecast ?? 'No forecast data', true) }}</pre>

                <h4 class="font-semibold mt-4">Customer Segments:</h4>
                <pre class="bg-gray-100 p-4 rounded text-sm overflow-auto">{{ print_r($customerSegments ?? 'No segments data', true) }}</pre>

                <h4 class="font-semibold mt-4">ML Demand Forecast:</h4>
                <pre class="bg-gray-100 p-4 rounded text-sm overflow-auto">{{ print_r($mlDemandForecast ?? 'No ML forecast data', true) }}</pre>

                <h4 class="font-semibold mt-4">Segment Recommendations:</h4>
                <pre class="bg-gray-100 p-4 rounded text-sm overflow-auto">{{ print_r($segmentRecommendations ?? 'No recommendations data', true) }}</pre>
            </div>
        </div>
    </div>
</div>
@endsection
