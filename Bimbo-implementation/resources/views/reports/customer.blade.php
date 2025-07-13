@extends('layouts.pdf')

@section('content')
    <h1>Customer Daily Report</h1>
    <p>Date: {{ $date }}</p>
    <h2>Your Activity</h2>
    <ul>
        <li>Orders Placed: {{ $reportData['summary']['orders_placed'] ?? 0 }}</li>
        <li>Total Spent: ${{ number_format($reportData['summary']['total_spent'] ?? 0, 2) }}</li>
        <li>Average Order Value: ${{ number_format($reportData['summary']['avg_order_value'] ?? 0, 2) }}</li>
    </ul>
@endsection 