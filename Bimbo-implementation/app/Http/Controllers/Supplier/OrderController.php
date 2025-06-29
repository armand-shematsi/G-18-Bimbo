<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    /**
     * Display the supplier's orders.
     */
    public function index()
    {
        // Get orders where the current user is the supplier
        $orders = Order::where('vendor_id', auth()->id())
            ->with(['user', 'items', 'payment'])
            ->latest()
            ->paginate(10);

        // Get order statistics
        $totalOrders = Order::where('vendor_id', auth()->id())->count();
        $pendingOrders = Order::where('vendor_id', auth()->id())->where('status', 'pending')->count();
        $processingOrders = Order::where('vendor_id', auth()->id())->where('status', 'processing')->count();
        $completedOrders = Order::where('vendor_id', auth()->id())->whereIn('status', ['delivered', 'shipped'])->count();

        return view('supplier.orders.index', compact('orders', 'totalOrders', 'pendingOrders', 'processingOrders', 'completedOrders'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        return view('supplier.orders.create');
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bakery_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        // TODO: Store the order in the database

        return redirect()->route('supplier.orders')
            ->with('success', 'Order created successfully.');
    }

    /**
     * Show a specific order.
     */
    public function show(Order $order)
    {
        // Ensure the order belongs to the current supplier
        if ($order->vendor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['user', 'items', 'payment']);

        return view('supplier.orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Ensure the order belongs to the current supplier
        if ($order->vendor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
}
