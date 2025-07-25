<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display the supplier's orders.
     */
    public function index()
    {
        // Show all orders for all suppliers (not just the current supplier)
        $orders = \App\Models\Order::whereHas('user', function ($query) {
            $query->where('role', 'bakery_manager');
        })
        ->with(['user', 'items', 'payment'])
        ->latest()
        ->get();

        // Get order statistics
        $totalOrders = $orders->count();
        $pendingOrders = $orders->where('status', 'pending')->count();
        $processingOrders = $orders->where('status', 'processing')->count();
        $completedOrders = $orders->whereIn('status', ['delivered', 'shipped'])->count();

        return view('supplier.orders.index', compact('orders', 'totalOrders', 'pendingOrders', 'processingOrders', 'completedOrders'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $user = auth()->user();
        $customerName = $user ? $user->name : '';
        $customerEmail = $user ? $user->email : '';
        // Return a view for creating a new order
        return view('supplier.orders.create', [
            'customerName' => $customerName,
            'customerEmail' => $customerEmail
        ]);
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
            DB::beginTransaction();

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
                    'product_id' => $item['product_id'], // Set product_id from request
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $itemTotal,
                ]);
            }

            // Update order total
            $order->update(['total' => $total]);

            DB::commit();

            // Log order creation
            \Log::info('Order created', [
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'vendor_id' => $order->vendor_id,
                'customer_name' => $order->customer_name
            ]);

            return redirect()->route('supplier.orders.index')
                ->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Failed to create order: ' . $e->getMessage()]);
        }
    }

    /**
     * Show a specific order.
     */
    public function show(Order $order)
    {
        // Remove vendor/user check so any supplier can view any order
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
        // Remove vendor/user check so any supplier can update any order
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $validated['status']]);

        \Log::info('Order status updated', [
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'new_status' => $validated['status']
        ]);

        // If delivered or processing, transfer inventory
        if (in_array($validated['status'], ['delivered', 'processing'])) {
            foreach ($order->items as $item) {
                // Decrease inventory for ALL suppliers for this product
                $allSupplierInventories = \App\Models\Inventory::where('product_id', $item->product_id)
                    ->where('location', 'supplier')
                    ->get();
                foreach ($allSupplierInventories as $supplierInventory) {
                    if ($supplierInventory->quantity >= $item->quantity) {
                        $supplierInventory->quantity -= $item->quantity;
                        $supplierInventory->save();
                    }
                }
                // If delivered, increase bakery inventory
                if ($validated['status'] === 'delivered') {
                    $bakeryInventory = \App\Models\Inventory::where('product_id', $item->product_id)
                        ->where('location', 'bakery')
                        ->first();
                    if ($bakeryInventory) {
                        $bakeryInventory->quantity += $item->quantity;
                        $bakeryInventory->save();
                    } else {
                        // Optionally create new bakery inventory record
                        \App\Models\Inventory::create([
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                            'location' => 'bakery',
                            // add other required fields as needed
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
}
