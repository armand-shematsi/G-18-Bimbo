@extends('layouts.retail-manager')

@section('header')
    Edit Order #{{ $order->id }}
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded p-6">
            <form method="POST" action="{{ route('retail.orders.update', $order->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="border rounded px-2 py-1">
                        <option value="pending" @if($order->status=='pending') selected @endif>Pending</option>
                        <option value="processing" @if($order->status=='processing') selected @endif>Processing</option>
                        <option value="shipped" @if($order->status=='shipped') selected @endif>Shipped</option>
                        <option value="delivered" @if($order->status=='delivered') selected @endif>Delivered</option>
                        <option value="cancelled" @if($order->status=='cancelled') selected @endif>Cancelled</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Total</label>
                    <input type="number" name="total" value="{{ $order->total }}" class="border rounded px-2 py-1 w-full">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Shipping Address</label>
                    <textarea name="shipping_address" class="border rounded px-2 py-1 w-full">{{ $order->shipping_address }}</textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Billing Address</label>
                    <textarea name="billing_address" class="border rounded px-2 py-1 w-full">{{ $order->billing_address }}</textarea>
                </div>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded">Update Order</button>
            </form>
        </div>
    </div>
</div>
@endsection 