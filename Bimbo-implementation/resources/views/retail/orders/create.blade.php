@extends('layouts.retail-manager')

@section('header')
    Place New Order
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-200 to-green-200 py-8">
    <div class="w-full max-w-4xl bg-white rounded-3xl shadow-2xl border-4 border-blue-600 p-0 md:p-10">
        <!-- Gradient Header -->
        <div class="rounded-t-3xl bg-gradient-to-r from-blue-800 to-green-600 py-8 px-4 text-center shadow-md">
            <h2 class="text-4xl font-extrabold text-white flex items-center justify-center gap-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Create New Order
            </h2>
        </div>
        <form id="orderForm" method="POST" action="{{ route('retail.orders.store') }}" class="space-y-10 px-4 md:px-0 pb-10">
            @csrf
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-100 border border-red-400 text-red-700">
                    <strong>There were some problems with your input:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- Section Divider -->
            <div class="flex items-center my-6">
                <span class="flex-grow border-t-4 border-blue-600"></span>
                <span class="mx-4 text-xl font-bold text-blue-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    Customer Information
                </span>
                <span class="flex-grow border-t-4 border-blue-600"></span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="customer_name" class="block text-base font-bold text-blue-800">Customer Name</label>
                    <input type="text" name="customer_name" id="customer_name" class="mt-1 block w-full rounded-lg border-2 border-blue-700 shadow-sm focus:border-blue-900 focus:ring-2 focus:ring-blue-700 bg-blue-100 text-blue-900 font-semibold transition-all duration-150" />
                    @error('customer_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="customer_email" class="block text-base font-bold text-blue-800">Email Address</label>
                    <input type="email" name="customer_email" id="customer_email" class="mt-1 block w-full rounded-lg border-2 border-blue-700 shadow-sm focus:border-blue-900 focus:ring-2 focus:ring-blue-700 bg-blue-100 text-blue-900 font-semibold transition-all duration-150" />
                    @error('customer_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div>
                    <label for="shipping_address" class="block text-base font-bold text-blue-800">Shipping Address</label>
                    <input type="text" name="shipping_address" id="shipping_address" class="mt-1 block w-full rounded-lg border-2 border-blue-700 shadow-sm focus:border-blue-900 focus:ring-2 focus:ring-blue-700 bg-blue-100 text-blue-900 font-semibold transition-all duration-150" required />
                </div>
                <div>
                    <label for="billing_address" class="block text-base font-bold text-blue-800">Billing Address</label>
                    <input type="text" name="billing_address" id="billing_address" class="mt-1 block w-full rounded-lg border-2 border-blue-700 shadow-sm focus:border-blue-900 focus:ring-2 focus:ring-blue-700 bg-blue-100 text-blue-900 font-semibold transition-all duration-150" required />
                </div>
            </div>
            <!-- Section Divider -->
            <div class="flex items-center my-6">
                <span class="flex-grow border-t-4 border-green-600"></span>
                <span class="mx-4 text-xl font-bold text-green-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-green-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" /></svg>
                    Payment Method
                </span>
                <span class="flex-grow border-t-4 border-green-600"></span>
            </div>
            <div class="mt-4">
                <select name="payment_method" id="payment_method" class="mt-1 block w-full rounded-lg border-2 border-green-700 shadow-sm focus:border-green-900 focus:ring-2 focus:ring-green-700 bg-green-100 text-green-900 font-semibold transition-all duration-150" required>
                    <option value="">Select payment method</option>
                    <option value="stripe">Stripe</option>
                    <option value="flutterwave">Flutterwave</option>
                    <option value="paystack">Paystack</option>
                    <option value="momo">MTN MoMo</option>
                </select>
            </div>
            <div class="mt-4">
                <label for="fulfillment_type" class="block text-base font-bold text-green-800">Fulfillment Type</label>
                <select name="fulfillment_type" id="fulfillment_type" class="mt-1 block w-full rounded-lg border-2 border-green-700 shadow-sm focus:border-green-900 focus:ring-2 focus:ring-green-700 bg-green-100 text-green-900 font-semibold transition-all duration-150" required>
                    <option value="">Select fulfillment type</option>
                    <option value="fba">Fulfilled by Amazon (FBA)</option>
                    <option value="fbm">Fulfilled by Merchant (FBM)</option>
                </select>
            </div>
            <div class="mt-4">
                <label for="tracking_number" class="block text-base font-bold text-green-800">Tracking Number (optional)</label>
                <input type="text" name="tracking_number" id="tracking_number" class="mt-1 block w-full rounded-lg border-2 border-green-700 shadow-sm focus:border-green-900 focus:ring-2 focus:ring-green-700 bg-green-100 text-green-900 font-semibold transition-all duration-150" />
            </div>
            <div class="mt-4">
                <label for="delivery_option" class="block text-base font-bold text-green-800">Delivery Option</label>
                <select name="delivery_option" id="delivery_option" class="mt-1 block w-full rounded-lg border-2 border-green-700 shadow-sm focus:border-green-900 focus:ring-2 focus:ring-green-700 bg-green-100 text-green-900 font-semibold transition-all duration-150" required>
                    <option value="">Select delivery option</option>
                    <option value="home">Home Delivery</option>
                    <option value="locker">Locker Pickup</option>
                    <option value="hub">Hub Pickup</option>
                </select>
            </div>
            <!-- Section Divider -->
            <div class="flex items-center my-6">
                <span class="flex-grow border-t-4 border-green-600"></span>
                <span class="mx-4 text-2xl font-extrabold text-green-800 flex items-center gap-2">
                    <svg class="w-7 h-7 text-green-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18" /></svg>
                    Order Items
                </span>
                <span class="flex-grow border-t-4 border-green-600"></span>
            </div>
            <div class="space-y-4" id="order-items">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4" id="order-item-row-0">
                    <div>
                        <label class="block text-base font-bold text-green-800">Product</label>
                        <select name="items[0][product_id]" class="product-select mt-1 block w-full rounded-lg border-2 border-green-700 shadow-sm focus:border-green-900 focus:ring-2 focus:ring-green-700 bg-green-100 text-green-900 font-semibold transition-all duration-150" required data-row="order-item-row-0">
                            <option value="">Select a product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-inventory-id="{{ $product->inventory_id ?? '' }}" data-name="{{ $product->name }}" data-price="{{ $product->unit_price ?? '' }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-base font-bold text-green-800">Quantity</label>
                        <input type="number" name="items[0][quantity]" min="1" class="mt-1 block w-full rounded-lg border-2 border-green-700 shadow-sm focus:border-green-900 focus:ring-2 focus:ring-green-700 bg-green-100 text-green-900 font-semibold transition-all duration-150" required />
                    </div>
                    <div>
                        <label class="block text-base font-bold text-green-800">Notes</label>
                        <input type="text" name="items[0][notes]" class="mt-1 block w-full rounded-lg border-2 border-green-700 shadow-sm focus:border-green-900 focus:ring-2 focus:ring-green-700 bg-green-100 text-green-900 font-semibold transition-all duration-150" />
                    </div>
                    <div>
                        <label class="block text-base font-bold text-green-800">Product Name</label>
                        <input type="text" name="items[0][product_name]" class="product-name mt-1 block w-full rounded-lg border-2 border-green-700 shadow-sm focus:border-green-900 focus:ring-2 focus:ring-green-700 bg-green-100 text-green-900 font-semibold transition-all duration-150" readonly required />
                    </div>
                    <div>
                        <label class="block text-base font-bold text-green-800">Unit Price</label>
                        <input type="number" name="items[0][unit_price]" class="unit-price mt-1 block w-full rounded-lg border-2 border-green-700 shadow-sm focus:border-green-900 focus:ring-2 focus:ring-green-700 bg-green-100 text-green-900 font-semibold transition-all duration-150" step="0.01" readonly required />
                    </div>
                    <input type="hidden" name="items[0][inventory_id]" class="inventory-id" value="">
                </div>
            </div>
            <button type="button" onclick="addOrderItem()" class="text-green-800 hover:text-green-900 font-bold transition flex items-center gap-2 mt-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                + Add Another Item
            </button>
            <!-- Section Divider -->
            <div class="flex items-center my-6">
                <span class="flex-grow border-t-4 border-blue-600"></span>
                <span class="mx-4 text-xl font-bold text-blue-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-7 4h4" /></svg>
                    Delivery Details
                </span>
                <span class="flex-grow border-t-4 border-blue-600"></span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="delivery_date" class="block text-base font-bold text-blue-800">Delivery Date</label>
                    <input type="date" name="delivery_date" id="delivery_date" class="mt-1 block w-full rounded-lg border-2 border-blue-700 shadow-sm focus:border-blue-900 focus:ring-2 focus:ring-blue-700 bg-blue-100 text-blue-900 font-semibold transition-all duration-150" />
                    @error('delivery_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="delivery_time" class="block text-base font-bold text-blue-800">Delivery Time</label>
                    <select name="delivery_time" id="delivery_time" class="mt-1 block w-full rounded-lg border-2 border-blue-700 shadow-sm focus:border-blue-900 focus:ring-2 focus:ring-blue-700 bg-blue-100 text-blue-900 font-semibold transition-all duration-150">
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
            <!-- Section Divider -->
            <div class="flex items-center my-6">
                <span class="flex-grow border-t-4 border-blue-600"></span>
                <span class="mx-4 text-xl font-bold text-blue-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6" /></svg>
                    Special Instructions
                </span>
                <span class="flex-grow border-t-4 border-blue-600"></span>
            </div>
            <div>
                <textarea name="special_instructions" id="special_instructions" rows="3" class="mt-1 block w-full rounded-lg border-2 border-blue-700 shadow-sm focus:border-blue-900 focus:ring-2 focus:ring-blue-700 bg-blue-100 text-blue-900 font-semibold transition-all duration-150"></textarea>
                @error('special_instructions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end mt-8">
                <button type="submit" class="flex items-center gap-2 bg-gradient-to-r from-green-700 to-blue-800 text-white px-10 py-4 rounded-2xl shadow-xl hover:from-green-800 hover:to-blue-900 font-bold text-xl transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-green-400 active:scale-95">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    Create Order
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let itemCount = 1;
    // Prepare product options as a JS string
    const productOptions = `
        <option value="">Select a product</option>
        @foreach($products as $product)
            <option value="{{ $product->id }}" data-inventory-id="{{ $product->inventory_id ?? '' }}" data-name="{{ $product->name }}" data-price="{{ $product->unit_price ?? '' }}">{{ $product->name }}</option>
        @endforeach
    `;
    function addOrderItem() {
        const rowId = `order-item-row-${itemCount}`;
        const template = `
            <div class=\"grid grid-cols-1 md:grid-cols-5 gap-4 mt-4\" id=\"${rowId}\">\n                <div>\n                    <label class=\"block text-sm font-medium text-gray-700\">Product</label>\n                    <select name=\"items[${itemCount}][product_id]\" class=\"product-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary\" required data-row=\"${rowId}\">${productOptions}</select>\n                </div>\n                <div>\n                    <label class=\"block text-sm font-medium text-gray-700\">Quantity</label>\n                    <input type=\"number\" name=\"items[${itemCount}][quantity]\" min=\"1\" class=\"mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary\" required>\n                </div>\n                <div>\n                    <label class=\"block text-sm font-medium text-gray-700\">Notes</label>\n                    <input type=\"text\" name=\"items[${itemCount}][notes]\" class=\"mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary\">\n                </div>\n                <div>\n                    <label class=\"block text-sm font-medium text-gray-700\">Product Name</label>\n                    <input type=\"text\" name=\"items[${itemCount}][product_name]\" class=\"product-name mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary\" readonly required>\n                </div>\n                <div>\n                    <label class=\"block text-sm font-medium text-gray-700\">Unit Price</label>\n                    <input type=\"number\" name=\"items[${itemCount}][unit_price]\" class=\"unit-price mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary\" step=\"0.01\" readonly required>\n                </div>\n                <input type=\"hidden\" name=\"items[${itemCount}][inventory_id]\" class=\"inventory-id\" value=\"\">\n            </div>\n        `;
        document.getElementById('order-items').insertAdjacentHTML('beforeend', template);
        itemCount++;
    }

    // Use event delegation for all .product-select elements
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const selected = e.target.options[e.target.selectedIndex];
            const name = selected.getAttribute('data-name') || '';
            const price = selected.getAttribute('data-price') || '';
            const inventoryId = selected.getAttribute('data-inventory-id') || '';
            const rowId = e.target.getAttribute('data-row');
            if (rowId) {
                const row = document.getElementById(rowId);
                if (row) {
                    const nameInput = row.querySelector('.product-name');
                    const priceInput = row.querySelector('.unit-price');
                    const inventoryIdInput = row.querySelector('.inventory-id');
                    if (nameInput) nameInput.value = name;
                    if (priceInput) priceInput.value = price;
                    if (inventoryIdInput) inventoryIdInput.value = inventoryId;
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

    // On form submit, check all inventory-id fields are set
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        let valid = true;
        document.querySelectorAll('.inventory-id').forEach(function(input) {
            if (!input.value) {
                valid = false;
            }
        });
        if (!valid) {
            e.preventDefault();
            alert('Please select a valid product for each order item.');
        }
    });
</script>
@endpush
@endsection
