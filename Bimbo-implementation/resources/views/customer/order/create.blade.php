@extends('layouts.app')

@section('content')
<style>
    body, .order-bg {
        background: linear-gradient(135deg, #e0e7ff 0%, #f0f9ff 100%) !important;
        min-height: 100vh;
    }
    .order-card {
        background: #fff;
        border-radius: 2rem;
        box-shadow: 0 8px 32px 0 rgba(59,130,246,0.13), 0 1.5px 8px 0 rgba(59,130,246,0.08);
        padding: 2.5rem 2rem 2rem 2rem;
        margin-top: 2rem;
        margin-bottom: 2rem;
        border: 1.5px solid #e0e7ff;
    }
    .order-section {
        margin-bottom: 2.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1.5px dashed #c7d2fe;
    }
    .order-section:last-child {
        border-bottom: none;
    }
    .order-title {
        font-size: 2.2rem;
        font-weight: 900;
        color: #2563eb;
        margin-bottom: 1.5rem;
        letter-spacing: 0.01em;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }
    .order-section-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #2563eb;
        margin-bottom: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .order-table th {
        background: #f1f5fd;
        color: #2563eb;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.04em;
    }
    .order-table td {
        font-size: 1rem;
    }
    .order-summary-box {
        background: linear-gradient(90deg, #e0e7ff 0%, #f0f9ff 100%);
        border-radius: 1.2rem;
        box-shadow: 0 2px 8px 0 rgba(59,130,246,0.08);
        padding: 1.5rem 1.2rem;
    }
    .order-cta {
        background: linear-gradient(90deg, #2563eb 0%, #38bdf8 100%);
        color: #fff;
        font-weight: 800;
        border-radius: 0.8rem;
        padding: 0.9rem 2.2rem;
        font-size: 1.1rem;
        box-shadow: 0 4px 16px 0 rgba(59,130,246,0.13);
        transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
    }
    .order-cta:hover {
        background: linear-gradient(90deg, #1d4ed8 0%, #0ea5e9 100%);
        color: #fff;
        box-shadow: 0 8px 32px 0 rgba(59,130,246,0.18);
        transform: scale(1.04);
    }
    .order-cancel {
        background: #e5e7eb;
        color: #374151;
        font-weight: 700;
        border-radius: 0.8rem;
        padding: 0.9rem 2.2rem;
        font-size: 1.1rem;
        margin-right: 0.7rem;
        transition: background 0.2s, color 0.2s;
    }
    .order-cancel:hover {
        background: #cbd5e1;
        color: #1e293b;
    }
</style>
<div class="order-bg min-h-screen">
    <div class="max-w-6xl mx-auto">
        <div class="order-card">
            <h1 class="order-title"><svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a4 4 0 004 4h10a4 4 0 004-4V7a4 4 0 00-4-4H7a4 4 0 00-4 4z" /></svg> Create New Order</h1>

            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Products Selection Table -->
            <div class="order-section">
                <h2 class="order-section-title"><svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg> Available Products</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg order-table">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left">Item Name</th>
                                <th class="px-6 py-3 text-left">Available Quantity</th>
                                <th class="px-6 py-3 text-left">Unit</th>
                                <th class="px-6 py-3 text-left">Unit Price</th>
                                <th class="px-6 py-3 text-left">Order Quantity</th>
                                <th class="px-6 py-3 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($inventory as $item)
                            <tr class="hover:bg-blue-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->item_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->unit }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-blue-700">${{ number_format($item->unit_price, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="number"
                                           name="order_quantity_{{ $item->id }}"
                                           id="order_quantity_{{ $item->id }}"
                                           min="1"
                                           max="{{ $item->quantity }}"
                                           value="1"
                                           class="w-20 border border-blue-200 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button type="button"
                                            onclick="addToCart({{ $item->id }}, '{{ $item->item_name }}', {{ $item->unit_price }}, '{{ $item->unit }}')"
                                            class="order-cta px-4 py-1 text-sm">
                                        Add to Cart
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No products available at the moment.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Shopping Cart -->
            <div class="order-section">
                <h2 class="order-section-title"><svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01" /></svg> Shopping Cart</h2>
                <div id="cart-items" class="bg-blue-50 rounded-lg p-4 min-h-32">
                    <div id="empty-cart-message" class="text-center text-gray-500 py-8">
                        Your cart is empty. Select products above to add them to your order.
                    </div>
                    <div id="cart-table" class="hidden">
                        <table class="min-w-full divide-y divide-gray-200 order-table">
                            <thead class="bg-white">
                                <tr>
                                    <th class="px-4 py-2 text-left">Item</th>
                                    <th class="px-4 py-2 text-left">Quantity</th>
                                    <th class="px-4 py-2 text-left">Unit Price</th>
                                    <th class="px-4 py-2 text-left">Subtotal</th>
                                    <th class="px-4 py-2 text-left">Action</th>
                                </tr>
                            </thead>
                            <tbody id="cart-tbody" class="bg-white divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="order-section">
                <div class="order-summary-box">
                    <h3 class="order-section-title"><svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg> Order Summary</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium" id="order-subtotal">$0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Delivery Fee:</span>
                            <span class="font-medium" id="delivery-fee">$5.00</span>
                        </div>
                        <div class="flex justify-between border-t pt-2">
                            <span class="text-lg font-semibold text-gray-800">Total:</span>
                            <span class="text-lg font-semibold text-gray-800" id="order-total">$5.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Form -->
            <form action="{{ route('customer.order.store') }}" method="POST" id="orderForm">
                @csrf
                <input type="hidden" name="items" id="cart-data-input">

                <!-- Delivery Information -->
                <div class="order-section">
                    <h3 class="order-section-title"><svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4m0 0V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2h6" /></svg> Delivery Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-2">Delivery Address</label>
                            <textarea name="delivery_address" id="delivery_address" rows="3" class="w-full border border-blue-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('delivery_address') }}</textarea>
                        </div>
                        <div>
                            <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-2">Preferred Delivery Date</label>
                            <input type="date" name="delivery_date" id="delivery_date" class="w-full border border-blue-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required value="{{ old('delivery_date') }}">
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="order-section">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full border border-blue-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Any special instructions or requests...">{{ old('notes') }}</textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('customer.orders.index') }}" class="order-cancel">
                        Cancel
                    </a>
                    <button type="submit" class="order-cta" id="submit-btn" disabled>
                        Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let cart = [];

function addToCart(itemId, itemName, unitPrice, unit) {
    const quantityInput = document.getElementById(`order_quantity_${itemId}`);
    const quantity = parseInt(quantityInput.value) || 0;

    if (quantity <= 0) {
        alert('Please enter a valid quantity');
        return;
    }

    // Check if item already exists in cart
    const existingItemIndex = cart.findIndex(item => item.id === itemId);

    if (existingItemIndex !== -1) {
        cart[existingItemIndex].quantity += quantity;
    } else {
        cart.push({
            id: itemId,
            name: itemName,
            quantity: quantity,
            unit_price: unitPrice,
            unit: unit
        });
    }

    // Reset quantity input
    quantityInput.value = 1;

    updateCartDisplay();
    updateOrderSummary();
    updateSubmitButton();
}

function removeFromCart(itemId) {
    cart = cart.filter(item => item.id !== itemId);
    updateCartDisplay();
    updateOrderSummary();
    updateSubmitButton();
}

function updateCartDisplay() {
    const cartTable = document.getElementById('cart-table');
    const emptyCartMessage = document.getElementById('empty-cart-message');
    const cartTbody = document.getElementById('cart-tbody');

    if (cart.length === 0) {
        cartTable.classList.add('hidden');
        emptyCartMessage.classList.remove('hidden');
        return;
    }

    cartTable.classList.remove('hidden');
    emptyCartMessage.classList.add('hidden');

    cartTbody.innerHTML = '';

    cart.forEach(item => {
        const subtotal = item.quantity * item.unit_price;
        const row = `
            <tr>
                <td class="px-4 py-2 text-sm text-gray-900">${item.name}</td>
                <td class="px-4 py-2 text-sm text-gray-900">${item.quantity} ${item.unit}</td>
                <td class="px-4 py-2 text-sm text-gray-900">$${item.unit_price.toFixed(2)}</td>
                <td class="px-4 py-2 text-sm font-medium text-gray-900">$${subtotal.toFixed(2)}</td>
                <td class="px-4 py-2">
                    <button type="button" onclick="removeFromCart(${item.id})" class="text-red-600 hover:text-red-800 text-sm">
                        Remove
                    </button>
                </td>
            </tr>
        `;
        cartTbody.innerHTML += row;
    });
}

function updateOrderSummary() {
    const subtotal = cart.reduce((total, item) => total + (item.quantity * item.unit_price), 0);
    const deliveryFee = 5.00;
    const total = subtotal + deliveryFee;

    document.getElementById('order-subtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('order-total').textContent = `$${total.toFixed(2)}`;

    // Update cart data input
    document.getElementById('cart-data-input').value = JSON.stringify(cart);

    // Enable/disable submit button
    const submitBtn = document.getElementById('submit-btn');
    if (cart.length > 0) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

function updateSubmitButton() {
    document.getElementById('submit-btn').disabled = cart.length === 0;
}

// Set cart data before submitting the form
const orderForm = document.getElementById('orderForm');
orderForm.addEventListener('submit', function(e) {
    document.getElementById('cart-data-input').value = JSON.stringify(cart);
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartDisplay();
    updateOrderSummary();
    updateSubmitButton();
});
</script>
@endsection
