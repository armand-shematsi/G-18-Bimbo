@extends('layouts.pdf')

@section('content')
    <h1>Bakery Manager Daily Report</h1>
    <p>Date: {{ $date }}</p>
    <h2>Production Summary</h2>
    <ul>
        <li>Batches Scheduled: {{ $reportData['summary']['batches_scheduled'] ?? 0 }}</li>
        <li>Batches Completed: {{ $reportData['summary']['batches_completed'] ?? 0 }}</li>
        <li>Batches In Progress: {{ $reportData['summary']['batches_in_progress'] ?? 0 }}</li>
    </ul>
    <h2>Ingredient Status</h2>
    @if(isset($reportData['ingredient_status']['low_stock_ingredients']))
        <ul>
            @foreach($reportData['ingredient_status']['low_stock_ingredients'] as $ingredient)
                <li>{{ $ingredient['ingredient_name'] }}: {{ $ingredient['current_quantity'] }}</li>
            @endforeach
        </ul>
    @else
        <p>No low stock ingredients.</p>
    @endif
@endsection 