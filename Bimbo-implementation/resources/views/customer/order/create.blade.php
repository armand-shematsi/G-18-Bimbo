@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Create New Order</h1>

            <!-- Products Selection Table -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Available Products</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($inventory as $item)
                            <tr class="hover:bg-gray-50">
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
                                    <div class="text-sm font-medium text-gray-900">${{ number_format($item->unit_price, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="number"
                                           name="order_quantity_{{ $item->id }}"
                                           id="order_quantity_{{ $item->id }}"
                                           min="1"
                                           max="{{ $item->quantity }}"
                                           value="1"
                                           class="w-20 border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button type="button"
                                            onclick="addToCart({{ $item->id }}, '{{ $item->item_name }}', {{ $item->unit_price }}, '{{ $item->unit }}')"
                                            class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 transition duration-200 text-sm">
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
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Shopping Cart</h2>

                <div id="cart-items" class="bg-gray-50 rounded-lg p-4 min-h-32">
                    <div id="empty-cart-message" class="text-center text-gray-500 py-8">
                        Your cart is empty. Select products above to add them to your order.
                    </div>
                    <div id="cart-table" class="hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-white">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="cart-tbody" class="bg-white divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Order Summary</h3>
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
                <input type="hidden" name="cart_data" id="cart-data-input">

                <!-- Delivery Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Delivery Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-2">Delivery Address</label>
                            <textarea name="delivery_address" id="delivery_address" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('delivery_address') }}</textarea>
                        </div>
                        <div>
                            <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-2">Preferred Delivery Date</label>
                            <input type="date" name="delivery_date" id="delivery_date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required value="{{ old('delivery_date') }}">
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Any special instructions or requests...">{{ old('notes') }}</textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('customer.orders.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-200" id="submit-btn" disabled>
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
}

function removeFromCart(itemId) {
    cart = cart.filter(item => item.id !== itemId);
    updateCartDisplay();
    updateOrderSummary();
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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartDisplay();
    updateOrderSummary();
});
</script>
@endsection
