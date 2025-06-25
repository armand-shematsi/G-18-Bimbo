@extends('layouts.retail-manager')

@section('header')
    Place New Order
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <form method="POST" action="{{ route('retail.orders.store') }}" class="space-y-6">
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

                    <!-- Order Details -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Order Items</h3>
                        
                        <div class="space-y-4" id="order-items">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                                    <select name="items[0][product_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="">Select a product</option>
                                        <option value="1">White Bread</option>
                                        <option value="2">Whole Wheat Bread</option>
                                        <option value="3">Multigrain Bread</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="items[0][quantity]" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                    <input type="text" name="items[0][notes]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
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
                <button type="submit" form="" class="fixed bottom-8 right-8 z-50 bg-green-600 text-white px-6 py-3 rounded-full shadow-lg hover:bg-green-700 transition-colors text-lg font-semibold">
                    Create Order
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let itemCount = 1;

    function addOrderItem() {
        const template = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                    <select name="items[${itemCount}][product_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        <option value="">Select a product</option>
                        <option value="1">White Bread</option>
                        <option value="2">Whole Wheat Bread</option>
                        <option value="3">Multigrain Bread</option>
                    </select>
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" name="items[${itemCount}][quantity]" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <input type="text" name="items[${itemCount}][notes]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                </div>
            </div>
        `;

        document.getElementById('order-items').insertAdjacentHTML('beforeend', template);
        itemCount++;
    }
</script>
@endpush
@endsection 