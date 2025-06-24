@extends('layouts.app')

@section('header')
    Orders List
@endsection

@section('content')
    <h1>Recent Orders</h1>
    @if(session('success'))
        <div class="text-green-600">{{ session('success') }}</div>
    @endif

    <ul>
        @foreach($orders as $order)
            <li>
                {{ $order->customer_name }} - {{ $order->delivery_date }} - {{ $order->delivery_time }}
            </li>
        @endforeach
    </ul>
@endsection 