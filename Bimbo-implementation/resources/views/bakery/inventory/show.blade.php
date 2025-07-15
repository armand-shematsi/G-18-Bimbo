@extends('layouts.app')

@section('content')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('bakery.bakery.inventory.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                    <i class="fas fa-arrow-left"></i> Back to Inventory
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $inventory->item_name }}</h1>
                    <p class="text-gray-600 mt-2">Inventory Item Details</p>
                </div>
            </div>
            <div class="flex space-x-3">
                @if($inventory)
                <a href="{{ route('bakery.inventory.edit', $inventory) }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                @endif
                <button onclick="openStockModal()"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Update Stock
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Item Details -->
        <div class="lg:col-span-2">
            <!-- Stock Level Chart -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Stock Level Trends</h2>
                <div class="h-64">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>

            <!-- Movement History Chart -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Movement History (Last 30 Days)</h2>
                <div class="h-64">
                    <canvas id="movementChart"></canvas>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Item Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Item Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $inventory->item_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Item Type</label>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1
                            @if($inventory->item_type == 'ingredient') bg-blue-100 text-blue-800
                            @elseif($inventory->item_type == 'finished_good') bg-green-100 text-green-800
                            @elseif($inventory->item_type == 'packaging') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $inventory->item_type)) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Current Quantity</label>
                        <p class="mt-1 text-sm text-gray-900" id="current-quantity">{{ $inventory->quantity }} {{ $inventory->unit }}</p>
                        <div class="mt-2 bg-gray-200 rounded-full h-2">
                            @php
                                $maxQuantity = max($inventory->quantity, $inventory->reorder_level * 2);
                                $percentage = $maxQuantity > 0 ? min(100, ($inventory->quantity / $maxQuantity) * 100) : 0;
                            @endphp
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                 id="stock-progress-bar"
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unit Price</label>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($inventory->unit_price)
                                ${{ number_format($inventory->unit_price, 2) }}
                            @else
                                Not set
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Reorder Level</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $inventory->reorder_level }} {{ $inventory->unit }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Storage Location</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $inventory->location ?: 'Not specified' }}</p>
                    </div>

                    @if($inventory->product)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Associated Product</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $inventory->product->name }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p class="mt-1">
                            @if($inventory->quantity == 0)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800" data-status>
                                    Out of Stock
                                </span>
                            @elseif($inventory->quantity <= $inventory->reorder_level)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800" data-status>
                                    Low Stock
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800" data-status>
                                    Available
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Affecting This Item -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Recent Orders Affecting This Item</h2>
                </div>
                <div class="p-6">
                    <div id="recent-orders" class="space-y-3">
                        <div class="text-center text-gray-500 py-4">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Loading recent orders...
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Metrics -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Key Metrics</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600" id="total-movements">0</div>
                        <div class="text-sm text-gray-600">Total Movements</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600" id="avg-stock-in">0</div>
                        <div class="text-sm text-gray-600">Avg Stock In/Day</div>
                    </div>
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600" id="avg-stock-out">0</div>
                        <div class="text-sm text-gray-600">Avg Stock Out/Day</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600" id="turnover-rate">0</div>
                        <div class="text-sm text-gray-600">Turnover Rate</div>
                    </div>
                </div>
            </div>

            <!-- Trend Analysis -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Trend Analysis</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 border rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600">Stock Trend</span>
                            <span id="stock-trend-indicator" class="text-sm font-medium">-</span>
                        </div>
                        <div class="text-lg font-semibold" id="stock-trend-value">-</div>
                    </div>
                    <div class="p-4 border rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600">Demand Pattern</span>
                            <span id="demand-pattern-indicator" class="text-sm font-medium">-</span>
                        </div>
                        <div class="text-lg font-semibold" id="demand-pattern-value">-</div>
                    </div>
                    <div class="p-4 border rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600">Reorder Frequency</span>
                            <span id="reorder-frequency-indicator" class="text-sm font-medium">-</span>
                        </div>
                        <div class="text-lg font-semibold" id="reorder-frequency-value">-</div>
                    </div>
                </div>
            </div>

            <!-- Stock Movement History -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Stock Movement History</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Change</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($movements as $movement)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $movement->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($movement->movement_type == 'in') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($movement->movement_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="@if($movement->quantity_change > 0) text-green-600 @else text-red-600 @endif">
                                            {{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }} {{ $inventory->unit }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $movement->notes ?: '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $movement->user ? $movement->user->name : 'System' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        No movement history available.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($movements->hasPages())
                    <div class="px-6 py-3 border-t border-gray-200">
                        {{ $movements->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="space-y-6">
            <!-- Stock Status Pie Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Stock Status Distribution</h3>
                <div class="h-48">
                    <canvas id="statusPieChart"></canvas>
                </div>
            </div>

            <!-- Stock Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Stock Status</h3>

                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Current Stock</span>
                            <span class="font-medium">{{ $inventory->quantity }} {{ $inventory->unit }}</span>
                        </div>
                        <div class="mt-2 bg-gray-200 rounded-full h-2">
                            @php
                                $percentage = $inventory->reorder_level > 0 ? min(100, ($inventory->quantity / $inventory->reorder_level) * 100) : 0;
                            @endphp
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Reorder Level</span>
                            <span class="font-medium">{{ $inventory->reorder_level }} {{ $inventory->unit }}</span>
                        </div>
                    </div>

                    @if($inventory->unit_price)
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Value</span>
                                <span class="font-medium">${{ number_format($inventory->quantity * $inventory->unit_price, 2) }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Last Updated</span>
                            <span class="font-medium" id="last-updated">{{ $inventory->updated_at->format('M d, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>

                <div class="space-y-3">
                    <button onclick="openStockModal('in')"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>Stock In
                    </button>

                    <button onclick="openStockModal('out')"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200">
                        <i class="fas fa-minus mr-2"></i>Stock Out
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Update Modal -->
<div id="stockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Stock</h3>

            <form id="stockForm" action="{{ route('bakery.inventory.update-stock', $inventory) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="movement_type" class="block text-sm font-medium text-gray-700 mb-2">Movement Type</label>
                    <select name="movement_type" id="movement_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="in">Stock In</option>
                        <option value="out">Stock Out</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="quantity_change" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <input type="number"
                           name="quantity_change"
                           id="quantity_change"
                           min="0.01"
                           step="0.01"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="0.00">
                </div>

                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea name="notes"
                              id="notes"
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Reason for stock change..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button"
                            onclick="closeStockModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                        Update Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openStockModal(type = null) {
    document.getElementById('stockModal').classList.remove('hidden');
    if (type) {
        document.getElementById('movement_type').value = type;
    }
}

function closeStockModal() {
    document.getElementById('stockModal').classList.add('hidden');
    document.getElementById('stockForm').reset();
}

// Close modal when clicking outside
document.getElementById('stockModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStockModal();
    }
});

// Real-time updates
let lastQuantity = {{ $inventory->quantity }};

function updateStockLevels() {
    fetch(`/bakery/api/inventory/${inventoryId}/live`)
        .then(response => response.json())
        .then(data => {
            // Check if quantity changed
            if (data.quantity !== lastQuantity) {
                // Show update notification
                showUpdateNotification(data.quantity, lastQuantity, data.unit);
                lastQuantity = data.quantity;
            }

            // Update current quantity
            document.getElementById('current-quantity').textContent = `${data.quantity} ${data.unit}`;

            // Update progress bar
            const maxQuantity = Math.max(data.quantity, data.reorder_level * 2);
            const percentage = maxQuantity > 0 ? Math.min(100, (data.quantity / maxQuantity) * 100) : 0;
            document.getElementById('stock-progress-bar').style.width = `${percentage}%`;

            // Update status if it changed
            const statusElement = document.querySelector('[data-status]');
            if (statusElement) {
                statusElement.textContent = data.status;
                statusElement.className = `inline-flex px-2 py-1 text-xs font-semibold rounded-full ${data.status_class}`;
            }

            // Update last updated timestamp
            const lastUpdatedElement = document.getElementById('last-updated');
            if (lastUpdatedElement && data.last_updated) {
                const date = new Date(data.last_updated);
                lastUpdatedElement.textContent = date.toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric'
                }) + ', ' + date.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        })
        .catch(error => console.error('Error updating stock levels:', error));
}

function showUpdateNotification(newQuantity, oldQuantity, unit) {
    const change = newQuantity - oldQuantity;
    const changeText = change > 0 ? `+${change}` : change.toString();
    const changeClass = change > 0 ? 'text-green-600' : 'text-red-600';

    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-white border border-gray-200 rounded-lg shadow-lg p-4 z-50';
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-sync-alt text-blue-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-900">Stock Updated</p>
                <p class="text-sm text-gray-500">
                    Quantity changed: <span class="${changeClass} font-medium">${changeText} ${unit}</span>
                </p>
            </div>
        </div>
    `;

    document.body.appendChild(notification);

    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function loadRecentOrders() {
    fetch(`/bakery/api/inventory/${inventoryId}/recent-orders`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('recent-orders');
            if (data.orders && data.orders.length > 0) {
                container.innerHTML = data.orders.map(order => `
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="font-medium text-gray-900">Order #${order.id}</div>
                            <div class="text-sm text-gray-500">${order.customer_name || 'N/A'}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-gray-900">${order.quantity} ${order.unit}</div>
                            <div class="text-xs text-gray-500">${order.created_at}</div>
                        </div>
                    </div>
                `).join('');
            } else {
                container.innerHTML = '<div class="text-center text-gray-500 py-4">No recent orders affecting this item.</div>';
            }
        })
        .catch(error => {
            console.error('Error loading recent orders:', error);
            document.getElementById('recent-orders').innerHTML = '<div class="text-center text-red-500 py-4">Error loading recent orders.</div>';
        });
}

// Initialize real-time updates
const inventoryId = {{ $inventory->id }};

// Chart instances
let stockChart, movementChart, statusPieChart;

// Initialize charts
function initializeCharts() {
    // Stock Level Chart
    const stockCtx = document.getElementById('stockChart').getContext('2d');
    stockChart = new Chart(stockCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Stock Level',
                data: [],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Reorder Level',
                data: [],
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                borderDash: [5, 5],
                tension: 0,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Stock Level Over Time'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Quantity'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }
            }
        }
    });

    // Movement History Chart
    const movementCtx = document.getElementById('movementChart').getContext('2d');
    movementChart = new Chart(movementCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Stock In',
                data: [],
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 1
            }, {
                label: 'Stock Out',
                data: [],
                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                borderColor: 'rgb(239, 68, 68)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Daily Movement Summary'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Quantity'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }
            }
        }
    });

    // Status Pie Chart
    const pieCtx = document.getElementById('statusPieChart').getContext('2d');
    statusPieChart = new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Available', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: [0, 0, 0],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    'rgb(34, 197, 94)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Current Status'
                }
            }
        }
    });
}

// Load chart data
function loadChartData() {
    console.log('Loading chart data for inventory:', inventoryId);

    fetch(`/bakery/api/inventory/${inventoryId}/chart-data`)
        .then(response => {
            console.log('Chart data response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Chart data received:', data);

            // Update stock level chart
            if (data.stockLevels && data.stockLevels.labels) {
                stockChart.data.labels = data.stockLevels.labels;
                stockChart.data.datasets[0].data = data.stockLevels.values;
                stockChart.data.datasets[1].data = data.stockLevels.reorderLevels;
                stockChart.update();
                console.log('Stock chart updated');
            }

            // Update movement chart
            if (data.movements && data.movements.labels) {
                movementChart.data.labels = data.movements.labels;
                movementChart.data.datasets[0].data = data.movements.stockIn;
                movementChart.data.datasets[1].data = data.movements.stockOut;
                movementChart.update();
                console.log('Movement chart updated');
            }

            // Update status pie chart
            if (data.statusDistribution) {
                statusPieChart.data.datasets[0].data = data.statusDistribution;
                statusPieChart.update();
                console.log('Status pie chart updated');
            }

            // Update key metrics
            if (data.keyMetrics) {
                document.getElementById('total-movements').textContent = data.keyMetrics.totalMovements;
                document.getElementById('avg-stock-in').textContent = data.keyMetrics.avgStockIn;
                document.getElementById('avg-stock-out').textContent = data.keyMetrics.avgStockOut;
                document.getElementById('turnover-rate').textContent = data.keyMetrics.turnoverRate + '%';
                console.log('Key metrics updated');
            }

            // Update trend analysis
            if (data.trendAnalysis) {
                // Stock Trend
                document.getElementById('stock-trend-indicator').textContent = data.trendAnalysis.stockTrend.trend;
                document.getElementById('stock-trend-indicator').className = `text-sm font-medium ${data.trendAnalysis.stockTrend.indicator}`;
                document.getElementById('stock-trend-value').textContent = data.trendAnalysis.stockTrend.value;

                // Demand Pattern
                document.getElementById('demand-pattern-indicator').textContent = data.trendAnalysis.demandPattern.pattern;
                document.getElementById('demand-pattern-indicator').className = `text-sm font-medium ${data.trendAnalysis.demandPattern.indicator}`;
                document.getElementById('demand-pattern-value').textContent = data.trendAnalysis.demandPattern.value;

                // Reorder Frequency
                document.getElementById('reorder-frequency-indicator').textContent = data.trendAnalysis.reorderFrequency.frequency;
                document.getElementById('reorder-frequency-indicator').className = `text-sm font-medium ${data.trendAnalysis.reorderFrequency.indicator}`;
                document.getElementById('reorder-frequency-value').textContent = data.trendAnalysis.reorderFrequency.value;
                console.log('Trend analysis updated');
            }
        })
        .catch(error => {
            console.error('Error loading chart data:', error);
            // Show error message on page
            showChartError('Failed to load chart data. Please refresh the page.');
        });
}

function showChartError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'fixed top-4 left-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    errorDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(errorDiv);

    setTimeout(() => {
        errorDiv.remove();
    }, 5000);
}

// Update stock levels every 30 seconds
setInterval(updateStockLevels, 30000);

// Load recent orders on page load
loadRecentOrders();

// Update recent orders every 2 minutes
setInterval(loadRecentOrders, 120000);

// Initialize charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    loadChartData();

    // Update chart data every 5 minutes
    setInterval(loadChartData, 300000);
});
</script>

@if(session('success'))
    <div id="success-message" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('success-message').style.display = 'none';
        }, 3000);
    </script>
@endif
@endsection
