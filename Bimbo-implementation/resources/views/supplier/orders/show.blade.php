@extends('layouts.supplier')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
            <p class="mt-1 text-sm text-gray-600">Order details and management</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('supplier.orders') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Orders
            </a>
        </div>
    </div>
@endsection

@section('navigation-links')
    <a href="{{ route('supplier.inventory.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Raw Material Inventory
    </a>
    <a href="{{ route('supplier.orders') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Order Requests
    </a>
    @php
    use App\Models\Message;
    use App\Models\User;

    $unreadCount = Message::where('receiver_id', auth()->id())
        ->where('is_read', false)
        ->whereIn('sender_id', User::where('role', 'retail_manager')->pluck('id'))
        ->count();
    @endphp
    <a href="{{ route('supplier.chat.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        Chat with Retail Managers & Customers
        @if($unreadCount > 0)
            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-500 text-white">{{ $unreadCount }}</span>
        @endif
    </a>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Order Status and Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Order Status</h3>
                    <div class="mt-2 flex items-center">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                               ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' :
                               ($order->status === 'shipped' ? 'bg-purple-100 text-purple-800' :
                               ($order->status === 'delivered' ? 'bg-green-100 text-green-800' :
                               'bg-red-100 text-red-800'))) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        <span class="ml-4 text-sm text-gray-500">Order placed on {{ $order->created_at->format('M d, Y \a\t H:i') }}</span>
                    </div>
                </div>

                <!-- Status Update Actions -->
                <div class="flex space-x-2">
                    @if($order->status === 'pending')
                        <form action="{{ route('supplier.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="processing">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Start Processing
                            </button>
                        </form>
                    @endif

                    @if($order->status === 'processing')
                        <form action="{{ route('supplier.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="shipped">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Mark as Shipped
                            </button>
                        </form>
                    @endif

                    @if($order->status === 'shipped')
                        <form action="{{ route('supplier.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="delivered">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Mark as Delivered
                            </button>
                        </form>
                    @endif

                    @if(in_array($order->status, ['pending', 'processing']))
                        <form action="{{ route('supplier.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel Order
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $order->customer_name ?? $order->user->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Customer Email</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $order->user->email ?? 'N/A' }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Shipping Address</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $order->shipping_address ?? 'N/A' }}</p>
                </div>
                @if($order->billing_address && $order->billing_address !== $order->shipping_address)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Billing Address</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->billing_address }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Order Items</h3>
            </div>
            <div class="overflow-x-auto">
                @if($order->items->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">${{ number_format($item->unit_price, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">${{ number_format($item->total_price, 2) }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-8">
                        <p class="text-sm text-gray-500">No items found for this order.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm text-gray-600">Subtotal:</span>
                        <span class="text-sm font-medium text-gray-900">${{ number_format($order->total ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm text-gray-600">Payment Status:</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' :
                               ($order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                               'bg-red-100 text-red-800') }}">
                            {{ ucfirst($order->payment_status ?? 'pending') }}
                        </span>
                    </div>
                    <div class="border-t border-gray-200 mt-4 pt-4">
                        <div class="flex justify-between">
                            <span class="text-lg font-semibold text-gray-900">Total:</span>
                            <span class="text-lg font-semibold text-gray-900">${{ number_format($order->total ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm text-gray-600">Order ID:</span>
                        <span class="text-sm font-medium text-gray-900">#{{ $order->id }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm text-gray-600">Placed:</span>
                        <span class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    @if($order->delivered_at)
                        <div class="flex justify-between py-2">
                            <span class="text-sm text-gray-600">Delivered:</span>
                            <span class="text-sm text-gray-900">{{ $order->delivered_at->format('M d, Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg" id="success-message">
                {{ session('success') }}
            </div>
            <script>
                setTimeout(function() {
                    document.getElementById('success-message').style.display = 'none';
                }, 3000);
            </script>
        @endif
    </div>
@endsection