@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Real-Time Product Demand Prediction</h2>
    <form method="GET" action="{{ route('admin.analytics.realtime-prediction') }}">
        <div class="mb-3">
            <label for="product" class="form-label">Product</label>
            <input type="text" class="form-control" id="product" name="product" value="{{ old('product', $product ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $date ?? '') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Get Prediction</button>
    </form>

    @if(isset($prediction))
        <div class="alert alert-success mt-4">
            <strong>Prediction:</strong> {{ $prediction }} units for <strong>{{ $product }}</strong> on <strong>{{ $date }}</strong>
        </div>
    @elseif(isset($error))
        <div class="alert alert-danger mt-4">
            <strong>Error:</strong> {{ $error }}
        </div>
    @endif
</div>
@endsection
