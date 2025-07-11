@extends('layouts.pdf')

@section('content')
    <h1>Admin Daily Report</h1>
    <p>Date: {{ $date }}</p>
    <h2>Platform Summary</h2>
    <ul>
        <li>Total Orders: {{ $reportData['summary']['total_orders'] ?? 0 }}</li>
        <li>Total Revenue: ${{ number_format($reportData['summary']['total_revenue'] ?? 0, 2) }}</li>
        <li>Active Vendors: {{ $reportData['summary']['active_vendors'] ?? 0 }}</li>
        <li>Pending Orders: {{ $reportData['summary']['pending_orders'] ?? 0 }}</li>
    </ul>
@endsection 