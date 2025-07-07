<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display orders that need delivery or are being delivered.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items', 'vendor'])
            ->whereIn('status', ['shipped', 'processing']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(15);

        // Get delivery statistics
        $pendingDeliveries = Order::where('status', 'shipped')->count();
        $inTransitDeliveries = Order::where('status', 'processing')->count();
        $completedDeliveries = Order::where('status', 'delivered')->whereDate('updated_at', today())->count();

        return view('distributor.orders.index', compact(
            'orders', 
            'pendingDeliveries', 
            'inTransitDeliveries', 
            'completedDeliveries'
        ));
    }

    /**
     * Show a specific order for delivery management.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items', 'vendor']);
        
        // Get delivery information if exists
        $delivery = Delivery::where('order_id', $order->id)->first();
        
        return view('distributor.orders.show', compact('order', 'delivery'));
    }

    /**
     * Update order status for delivery tracking.
     */
    public function updateDeliveryStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:processing,shipped,delivered',
            'tracking_number' => 'nullable|string',
            'delivery_notes' => 'nullable|string',
        ]);

        $order->update([
            'status' => $validated['status'],
            'tracking_number' => $validated['tracking_number'] ?? $order->tracking_number,
        ]);

        // Create or update delivery record
        Delivery::updateOrCreate(
            ['order_id' => $order->id],
            [
                'status' => $validated['status'],
                'notes' => $validated['delivery_notes'] ?? null,
                'updated_at' => now(),
            ]
        );

        // If delivered, update delivered_at timestamp
        if ($validated['status'] === 'delivered') {
            $order->update(['delivered_at' => now()]);
        }

        return redirect()->route('distributor.orders.show', $order)
            ->with('success', 'Delivery status updated successfully.');
    }

    /**
     * Get orders for delivery route planning.
     */
    public function routeOrders()
    {
        $orders = Order::with(['user', 'vendor'])
            ->whereIn('status', ['shipped', 'processing'])
            ->whereNotNull('shipping_address')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'shipping_address' => $order->shipping_address,
                    'status' => $order->status,
                    'total' => $order->total,
                    'created_at' => $order->created_at->format('M d, Y'),
                ];
            });

        return response()->json($orders);
    }

    /**
     * Get delivery statistics for dashboard.
     */
    public function deliveryStats()
    {
        $stats = [
            'pending' => Order::where('status', 'shipped')->count(),
            'in_transit' => Order::where('status', 'processing')->count(),
            'completed_today' => Order::where('status', 'delivered')
                ->whereDate('updated_at', today())->count(),
            'total_delivered' => Order::where('status', 'delivered')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get orders API for real-time updates.
     */
    public function apiOrders(Request $request)
    {
        $query = Order::with(['user', 'vendor'])
            ->whereIn('status', ['shipped', 'processing']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->take(10)->get();

        return response()->json($orders);
    }
} 