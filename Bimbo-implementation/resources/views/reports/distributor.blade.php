@extends('layouts.pdf')

@section('content')
    <h1>Distributor Daily Report</h1>
    <p>Date: {{ $date }}</p>
    <h2>Delivery Summary</h2>
    <ul>
        <li>Orders to Deliver: {{ $reportData['summary']['orders_to_deliver'] ?? 0 }}</li>
        <li>Orders Delivered Today: {{ $reportData['summary']['orders_delivered_today'] ?? 0 }}</li>
    </ul>
    <h2>Pending Deliveries</h2>
    @if(isset($reportData['delivery_status']['pending_deliveries']))
        <ul>
            @foreach($reportData['delivery_status']['pending_deliveries'] as $order)
                <li>Order #{{ $order['order_id'] }} - {{ $order['customer'] }} ({{ $order['status'] }})</li>
            @endforeach
        </ul>
    @else
        <p>No pending deliveries.</p>
    @endif
@endsection 