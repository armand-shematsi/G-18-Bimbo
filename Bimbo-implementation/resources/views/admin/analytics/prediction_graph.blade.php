@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Prediction Graph</h2>
    <form method="GET" action="{{ route('admin.analytics.prediction-graph') }}">
        <div class="mb-3">
            <label for="product" class="form-label">Product</label>
            <select class="form-control" id="product" name="product" required>
                <option value="">Select a product</option>
                @foreach($products ?? [] as $prod)
                    <option value="{{ $prod }}" {{ (old('product', $product ?? '') == $prod) ? 'selected' : '' }}>{{ $prod }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $startDate ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="days" class="form-label">Days</label>
            <input type="number" class="form-control" id="days" name="days" value="{{ old('days', $days ?? 30) }}" min="1" max="60" required>
        </div>
        <button type="submit" class="btn btn-primary">Show Graph</button>
    </form>

    @if(isset($error))
        <div class="alert alert-danger mt-4">
            <strong>Error:</strong> {{ $error }}
        </div>
    @endif

    @if(!empty($predictions))
        <div class="mt-5">
            <canvas id="predictionChart"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const labels = {!! json_encode(array_column($predictions, 'date')) !!};
            const data = {!! json_encode(array_column($predictions, 'predicted_quantity')) !!};
            new Chart(document.getElementById('predictionChart'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Predicted Quantity',
                        data: data,
                        borderColor: 'blue',
                        fill: false
                    }]
                }
            });
        </script>
    @endif
</div>
@endsection
