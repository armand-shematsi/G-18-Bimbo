<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Vendor;

class OrderController extends Controller
{
    /**
     * Display the supplier's orders.
     */
    public function index()
    {
        // Get the first vendor's id (used when creating orders)
        $vendorId = \App\Models\Vendor::query()->value('id');
        $orders = Order::where('vendor_id', $vendorId)
            ->with(['user', 'items', 'payment'])
            ->latest()
            ->paginate(10);

        // Get order statistics
        $totalOrders = Order::where('vendor_id', $vendorId)->count();
        $pendingOrders = Order::where('vendor_id', $vendorId)->where('status', 'pending')->count();
        $processingOrders = Order::where('vendor_id', $vendorId)->where('status', 'processing')->count();
        $completedOrders = Order::where('vendor_id', $vendorId)->whereIn('status', ['delivered', 'shipped'])->count();

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
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            \DB::beginTransaction();

            // Get the first existing vendor's id
            $vendorId = Vendor::query()->value('id');
            if (!$vendorId) {
                throw new \Exception('No vendor exists in the database.');
            }

            // Create the order
            $order = Order::create([
                'vendor_id' => $vendorId, // Use existing vendor id
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'status' => 'pending',
            ]);

            // Calculate total and create order items
            $total = 0;
            foreach ($validated['items'] as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $total += $itemTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => null, // Custom product, not from inventory
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
            return back()->withInput()->withErrors(['error' => 'Failed to create order: ' . $e->getMessage()]);
        }
    }

    /**
     * Show a specific order.
     */
    public function show(Order $order)
    {
        // Use the first vendor's id (used when creating orders)
        $vendorId = \App\Models\Vendor::query()->value('id');
        // Ensure the order belongs to the current supplier
        if ($order->vendor_id !== $vendorId) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['user', 'items', 'payment']);

        return view('supplier.orders.show', compact('order'));
    }

    /**
     * Update the specified order.
     */
    public function update(Request $request, Order $order)
    {
        // Use the first vendor's id (used when creating orders)
        $vendorId = \App\Models\Vendor::query()->value('id');
        // Ensure the order belongs to the current supplier
        if ($order->vendor_id !== $vendorId) {
            abort(403, 'Unauthorized access to this order.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('supplier.orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Use the first vendor's id (used when creating orders)
        $vendorId = \App\Models\Vendor::query()->value('id');
        // Ensure the order belongs to the current supplier
        if ($order->vendor_id !== $vendorId) {
            abort(403, 'Unauthorized access to this order.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
}
