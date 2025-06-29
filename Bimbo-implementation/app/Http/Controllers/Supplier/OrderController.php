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
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'shipping_address' => 'required|string',
            'billing_address' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            \DB::beginTransaction();

            // Create the order
            $order = Order::create([
                'user_id' => auth()->id(), // The supplier creating the order
                'vendor_id' => auth()->id(), // The supplier is also the vendor
                'customer_name' => $validated['customer_name'],
                'status' => 'pending',
                'payment_status' => 'pending',
                'shipping_address' => $validated['shipping_address'],
                'billing_address' => $validated['billing_address'] ?? $validated['shipping_address'],
                'placed_at' => now(),
            ]);

            // Calculate total and create order items
            $total = 0;
            foreach ($validated['items'] as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $total += $itemTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $itemTotal,
                ]);
            }

            // Update order total
            $order->update(['total' => $total]);

            \DB::commit();

            return redirect()->route('supplier.orders')
                ->with('success', 'Order created successfully.');

        } catch (\Exception $e) {
            \DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Failed to create order. Please try again.']);
        }
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
