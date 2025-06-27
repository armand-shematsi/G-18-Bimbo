@extends('layouts.retail-manager')

@section('header')
    Place New Order
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form id="orderForm" method="POST" action="{{ route('retail.orders.store') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Customer Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700">Customer Name</label>
                            <input type="text" name="customer_name" id="customer_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            @error('customer_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="customer_email" id="customer_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            @error('customer_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Shipping and Billing Address -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700">Shipping Address</label>
                            <input type="text" name="shipping_address" id="shipping_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                        </div>
                        <div>
                            <label for="billing_address" class="block text-sm font-medium text-gray-700">Billing Address</label>
                            <input type="text" name="billing_address" id="billing_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mt-4">
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                            <option value="">Select payment method</option>
                            <option value="stripe">Stripe</option>
                            <option value="flutterwave">Flutterwave</option>
                            <option value="paystack">Paystack</option>
                            <option value="momo">MTN MoMo</option>
                        </select>
                    </div>

                    <!-- Order Details -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Order Items</h3>
                        
                        <div class="space-y-4" id="order-items">
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4" id="order-item-row-0">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Product</label>
                                    <select name="items[0][product_id]" class="product-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required data-row="order-item-row-0">
                                        <option value="">Select a product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-name="{{ $product->item_name }}" data-price="{{ $product->unit_price }}">{{ $product->item_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="items[0][quantity]" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                                    <input type="text" name="items[0][notes]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Product Name</label>
                                    <input type="text" name="items[0][product_name]" class="product-name mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" readonly required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Unit Price</label>
                                    <input type="number" name="items[0][unit_price]" class="unit-price mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" step="0.01" readonly required>
                                </div>
                            </div>
                        </div>

                        <button type="button" onclick="addOrderItem()" class="text-primary hover:text-primary/80">
                            + Add Another Item
                        </button>
                    </div>

                    <!-- Delivery Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="delivery_date" class="block text-sm font-medium text-gray-700">Delivery Date</label>
                            <input type="date" name="delivery_date" id="delivery_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            @error('delivery_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="delivery_time" class="block text-sm font-medium text-gray-700">Delivery Time</label>
                            <select name="delivery_time" id="delivery_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">Select delivery time</option>
                                <option value="morning">Morning (8:00 - 12:00)</option>
                                <option value="afternoon">Afternoon (12:00 - 16:00)</option>
                                <option value="evening">Evening (16:00 - 20:00)</option>
                            </select>
                            @error('delivery_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Special Instructions -->
                    <div>
                        <label for="special_instructions" class="block text-sm font-medium text-gray-700">Special Instructions</label>
                        <textarea name="special_instructions" id="special_instructions" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"></textarea>
                        @error('special_instructions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/90 transition-colors">
                            Place Order
                        </button>
                    </div>
                </form>
                <!-- Floating Create Order Button -->
                <button
                    type="submit"
                    form="orderForm"
                    id="createOrderBtn"
                    aria-label="Create Order"
                    class="fixed bottom-8 right-8 z-50 bg-green-600 text-white px-6 py-3 rounded-full shadow-lg hover:bg-green-700 transition-colors text-lg font-semibold focus:outline-none focus:ring-2 focus:ring-green-400"
                    style="min-width: 160px;"
                >
                    <span id="createOrderBtnText">Create Order</span>
                    <span id="createOrderBtnSpinner" class="hidden ml-2"><svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg></span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let itemCount = 1;
    // Prepare product options as a JS string
    const productOptions = `
        <option value="">Select a product</option>
        @foreach($products as $product)
            <option value="{{ $product->id }}" data-name="{{ $product->item_name }}" data-price="{{ $product->unit_price }}">{{ $product->item_name }}</option>
        @endforeach
    `;
    function addOrderItem() {
        const rowId = `order-item-row-${itemCount}`;
        const template = `
            <div class=\"grid grid-cols-1 md:grid-cols-5 gap-4 mt-4\" id=\"${rowId}\">\n                <div>\n                    <label class=\"block text-sm font-medium text-gray-700\">Product</label>\n                    <select name=\"items[${itemCount}][product_id]\" class=\"product-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary\" required data-row=\"${rowId}\">${productOptions}</select>\n                </div>\n                <div>\n                    <label class=\"block text-sm font-medium text-gray-700\">Quantity</label>\n                    <input type=\"number\" name=\"items[${itemCount}][quantity]\" min=\"1\" class=\"mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary\" required>\n                </div>\n                <div>\n                    <label class=\"block text-sm font-medium text-gray-700\">Notes</label>\n                    <input type=\"text\" name=\"items[${itemCount}][notes]\" class=\"mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary\">\n                </div>\n                <div>\n                    <label class=\"block text-sm font-medium text-gray-700\">Product Name</label>\n                    <input type=\"text\" name=\"items[${itemCount}][product_name]\" class=\"product-name mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary\" readonly required>\n                </div>\n                <div>\n                    <label class=\"block text-sm font-medium text-gray-700\">Unit Price</label>\n                    <input type=\"number\" name=\"items[${itemCount}][unit_price]\" class=\"unit-price mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary\" step=\"0.01\" readonly required>\n                </div>\n            </div>\n        `;
        document.getElementById('order-items').insertAdjacentHTML('beforeend', template);
        itemCount++;
    }

    // Use event delegation for all .product-select elements
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const selected = e.target.options[e.target.selectedIndex];
            const name = selected.getAttribute('data-name') || '';
            const price = selected.getAttribute('data-price') || '';
            const rowId = e.target.getAttribute('data-row');
            if (rowId) {
                const row = document.getElementById(rowId);
                if (row) {
                    const nameInput = row.querySelector('.product-name');
                    const priceInput = row.querySelector('.unit-price');
                    if (nameInput) nameInput.value = name;
                    if (priceInput) priceInput.value = price;
                }
            }
        }
    });

    // Button loading state
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        var btn = document.getElementById('createOrderBtn');
        var btnText = document.getElementById('createOrderBtnText');
        var btnSpinner = document.getElementById('createOrderBtnSpinner');
        btn.disabled = true;
        btnText.classList.add('hidden');
        btnSpinner.classList.remove('hidden');
    });
</script>
@endpush
@endsection 