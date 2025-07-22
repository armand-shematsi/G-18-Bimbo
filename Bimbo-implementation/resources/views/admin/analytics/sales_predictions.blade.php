@extends('layouts.dashboard')

@section('header')
    <h2 class="font-semibold text-2xl text-blue-900 leading-tight tracking-wide">
        <span class="inline-block align-middle mr-2">
            <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v4a1 1 0 001 1h3m10-5v4a1 1 0 01-1 1h-3m-4 4h4m-2 0v4m0 0h-2"/></svg>
        </span>
        Sales Predictions & Forecasts
    </h2>
@endsection

@section('content')
<div class="py-12 bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-blue-100 hover:shadow-2xl transition-all duration-200">
            <h3 class="text-2xl font-bold text-blue-700 mb-6 flex items-center gap-2">
                <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v4a1 1 0 001 1h3m10-5v4a1 1 0 01-1 1h-3m-4 4h4m-2 0v4m0 0h-2"/></svg>
                Predicted Sales (Next 7 Days)
            </h3>
            <div class="mb-6">
                <label for="predProductSelect" class="block mb-2 font-semibold text-blue-900">Select Product:</label>
                <select id="predProductSelect" class="border border-blue-200 rounded-lg px-3 py-2 mb-4 focus:ring-2 focus:ring-blue-200 w-full max-w-xs">
                    @if(isset($finishedProducts) && count($finishedProducts))
                        <optgroup label="Finished Products">
                            @foreach($finishedProducts as $product)
                                <option value="{{ strtolower($product) }}">{{ $product }}</option>
                            @endforeach
                        </optgroup>
                    @endif
                    @if(isset($rawMaterials) && count($rawMaterials))
                        <optgroup label="Raw Materials">
                            @foreach($rawMaterials as $material)
                                <option value="{{ strtolower($material) }}">{{ $material }}</option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
            </div>
            <div class="bg-gradient-to-br from-blue-100 via-white to-purple-100 rounded-xl p-6 flex items-center justify-center min-h-[350px]">
                <canvas id="predChart" class="w-full h-72 md:h-96"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const salesForecast = @json($salesForecastChartData ?? []);
    const predProductSelect = document.getElementById('predProductSelect');
    const predCtx = document.getElementById('predChart').getContext('2d');
    function getPredChartData(product) {
        const forecast = salesForecast[product] || [];
        const forecastDates = forecast.map(f => f.date);
        const forecastValues = forecast.map(f => f.predicted);
        return {
            labels: forecastDates,
            datasets: [
                {
                    label: 'Predicted Sales',
                    data: forecastValues,
                    borderColor: '#f59e42',
                    backgroundColor: 'rgba(245,158,66,0.1)',
                    tension: 0.2,
                }
            ]
        };
    }
    let predChart;
    function renderPredChart(product) {
        const data = getPredChartData(product);
        if (predChart) predChart.destroy();
        predChart = new Chart(predCtx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: product.charAt(0).toUpperCase() + product.slice(1) + ' Predicted Sales' },
                    tooltip: { enabled: true },
                },
                scales: {
                    x: { title: { display: true, text: 'Date' } },
                    y: { beginAtZero: true, title: { display: true, text: 'Predicted Sales' } }
                }
            }
        });
    }
    if (predProductSelect && predProductSelect.value) {
        renderPredChart(predProductSelect.value);
        predProductSelect.addEventListener('change', function() {
            renderPredChart(this.value);
        });
    }
</script>
@endpush
