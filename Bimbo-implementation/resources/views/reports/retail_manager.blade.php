@extends('layouts.pdf')

@section('content')
    <h1>Retail Manager Daily Report</h1>
    <p>Date: {{ $date }}</p>
    <h2>Sales Summary</h2>
    <ul>
        <li>Total Sales: ${{ number_format($reportData['summary']['total_sales'] ?? 0, 2) }}</li>
        <li>Total Orders: {{ $reportData['summary']['total_orders'] ?? 0 }}</li>
        <li>Average Order Value: ${{ number_format($reportData['summary']['avg_order_value'] ?? 0, 2) }}</li>
    </ul>
    <h2>Top Products</h2>
    @if(isset($reportData['top_products']))
        <ul>
            @foreach($reportData['top_products'] as $product)
                <li>{{ $product->name }}: {{ $product->total_sold }} sold, ${{ number_format($product->total_revenue, 2) }} revenue</li>
            @endforeach
        </ul>
    @else
        <p>No top products data.</p>
    @endif
@endsection 