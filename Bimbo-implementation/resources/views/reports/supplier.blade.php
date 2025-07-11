@extends('layouts.pdf')

@section('content')
    <h1>Supplier Daily Report</h1>
    <p>Date: {{ $date }}</p>
    <h2>Order Summary</h2>
    <ul>
        <li>Orders Received: {{ $reportData['summary']['orders_received'] ?? 0 }}</li>
        <li>Total Order Value: ${{ number_format($reportData['summary']['total_order_value'] ?? 0, 2) }}</li>
    </ul>
    <h2>Inventory Status</h2>
    @if(isset($reportData['inventory_status']['items_needing_restock']))
        <ul>
            @foreach($reportData['inventory_status']['items_needing_restock'] as $item)
                <li>{{ $item['product_name'] }}: {{ $item['current_quantity'] }} (Reorder Level: {{ $item['reorder_level'] }})</li>
            @endforeach
        </ul>
    @else
        <p>No items needing restock.</p>
    @endif
@endsection 