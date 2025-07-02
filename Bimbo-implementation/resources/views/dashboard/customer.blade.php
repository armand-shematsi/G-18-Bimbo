@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Customer Dashboard') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-semibold mb-4">Welcome, {{ Auth::user()->name }}!</h3>

                <p>Email: {{ Auth::user()->email }}</p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Chat with Suppliers -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h4 class="text-lg font-medium text-blue-900 mb-2">Chat with Suppliers</h4>
                        <p class="text-blue-700 mb-4">Get support and ask questions directly to suppliers.</p>
                        <a href="{{ route('customer.chat.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            Start Chat
                        </a>
                    </div>

                    <!-- Place an Order -->
                    <div class="bg-yellow-50 p-6 rounded-lg">
                        <h4 class="text-lg font-medium text-yellow-900 mb-2">Place an Order</h4>
                        <p class="text-yellow-700 mb-4">Create a new order for your needs.</p>
                        <a href="{{ route('customer.orders.create') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition">
                            Create Order
                        </a>
                    </div>

                    <!-- View Orders -->
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h4 class="text-lg font-medium text-green-900 mb-2">My Orders</h4>
                        <p class="text-green-700 mb-4">View and track your order history.</p>
                        <a href="#orders-section" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                            View Orders
                        </a>
                    </div>

                    <!-- Profile -->
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <h4 class="text-lg font-medium text-purple-900 mb-2">Profile</h4>
                        <p class="text-purple-700 mb-4">Update your account information.</p>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">
                            Edit Profile
                        </a>
                    </div>
                </div>

                <!-- Orders Section -->
                <div id="orders-section" class="mt-12">
                    <h4 class="text-xl font-semibold mb-4">Recent Orders</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 mb-6">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Placed At</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($orders as $order)
                                <tr>
                                    <td class="px-4 py-2">{{ $order->id }}</td>
                                    <td class="px-4 py-2">{{ ucfirst($order->status) }}</td>
                                    <td class="px-4 py-2">${{ number_format($order->total, 2) }}</td>
                                    <td class="px-4 py-2">{{ $order->placed_at ? $order->placed_at->format('Y-m-d H:i') : '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-center">No orders found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Orders Chart -->
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <h5 class="text-lg font-medium text-gray-900 mb-4">Order Totals Over Time</h5>
                        <canvas id="ordersChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('ordersChart').getContext('2d');
        var ordersData = @json($orders->reverse()->map(fn($order) => $order->total));
        var ordersLabels = @json($orders->reverse()->map(fn($order) => $order->placed_at ? $order->placed_at->format('M d') : ''));
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ordersLabels,
                datasets: [{
                    label: 'Order Total ($)',
                    data: ordersData,
                    borderColor: 'rgba(34,197,94,1)',
                    backgroundColor: 'rgba(34,197,94,0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
</script>
@endsection
