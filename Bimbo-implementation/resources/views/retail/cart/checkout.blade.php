@extends('layouts.retail-manager')

@section('header')
    Checkout
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded p-6">
            <h2 class="text-xl font-semibold mb-4">Checkout</h2>
            @if(count($cart) > 0)
            <table class="min-w-full divide-y divide-gray-200 mb-4">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Product</th>
                        <th class="px-4 py-2">Quantity</th>
                        <th class="px-4 py-2">Unit Price</th>
                        <th class="px-4 py-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $item)
                    <tr>
                        <td class="px-4 py-2">{{ $item['product_name'] ?? 'Unknown Product' }}</td>
                        <td class="px-4 py-2">{{ $item['quantity'] ?? 0 }}</td>
                        <td class="px-4 py-2">${{ number_format($item['unit_price'] ?? 0, 2) }}</td>
                        <td class="px-4 py-2">${{ number_format($item['total_price'] ?? 0, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <form method="POST" action="{{ route('cart.processCheckout') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Shipping Address</label>
                    <textarea name="shipping_address" class="border rounded px-2 py-1 w-full" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Billing Address</label>
                    <textarea name="billing_address" class="border rounded px-2 py-1 w-full" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                    <select name="payment_method" class="border rounded px-2 py-1 w-full" required>
                        <option value="stripe">Stripe</option>
                        <option value="flutterwave">Flutterwave</option>
                        <option value="paystack">Paystack</option>
                        <option value="mtn_momo">MTN MoMo</option>
                    </select>
                </div>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded">Confirm Order</button>
            </form>
            @else
            <div>No items in cart.</div>
            @endif
        </div>
    </div>
</div>
@endsection
