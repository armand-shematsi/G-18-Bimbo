<?php

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Notifications\OrderPlaced;
use App\Notifications\OrderStatusUpdated;
use App\Http\Requests\StoreOrderRequest;
use App\Models\ActivityLog;
use App\Models\Inventory;
use App\Models\Product;

class OrderController extends Controller
{
    // List all orders (with search/filter stub)
    public function index(Request $request)
    {
        $orders = Order::whereHas('user', function($query) {
            $query->where('role', 'customer');
        })
        ->whereHas('items.product', function($query) {
            $query->where('type', 'finished_product');
        })
        ->latest()->paginate(20);
        return view('retail.orders.index', compact('orders'));
    }

    // Show order creation form
    public function create()
    {
        $products = Product::where('type', 'finished_product')->get()->map(function($product) {
            $inventory = \App\Models\Inventory::where('item_name', $product->name)->first();
            $product->inventory_id = $inventory ? $inventory->id : null;
            return $product;
        });
        return view('retail.orders.create', compact('products'));
    }

    // Store new order and items
    public function store(StoreOrderRequest $request)
    {
        \Log::info('OrderController@store called', ['request' => $request->all()]);
        // Calculate total from items
        $total = collect($request->items)->sum(function($item) {
            return $item['quantity'] * $item['unit_price'];
        });
        // Create order with correct user_id and total
        $order = Order::create([
            'user_id' => auth()->id(),
            'vendor_id' => 3, // Always set to bakery manager with user ID 3
            'customer_name' => $request->customer_name,
            'status' => 'pending',
            'total' => $total,
            'payment_status' => 'unpaid',
            'shipping_address' => $request->shipping_address,
            'billing_address' => $request->billing_address,
            'placed_at' => now(),
            'fulfillment_type' => $request->fulfillment_type,
            'tracking_number' => $request->tracking_number,
            'delivery_option' => $request->delivery_option,
        ]);
        // Create order items and deduct inventory immediately
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $item['total_price'] = $item['quantity'] * $item['unit_price'];
                // Ensure product_id and inventory_id are set
                if (isset($item['inventory_id'])) {
                    $inventory = \App\Models\Inventory::find($item['inventory_id']);
                    if ($inventory) {
                        $item['product_id'] = $inventory->product_id;
                        $item['inventory_id'] = $inventory->id;
                        // Deduct inventory immediately
                        if ($inventory->quantity >= $item['quantity']) {
                            $inventory->quantity -= $item['quantity'];
                            $inventory->save();
                        } else {
                            // Optionally, handle insufficient stock (skip, error, etc.)
                        }
                    }
                }
                $order->items()->create($item);
            }
        }
        // Notify customer
        if ($order->user) {
            $order->user->notify(new OrderPlaced($order));
        }
        // Notify Bakery Manager (both email and in-app)
        $bakeryManager = \App\Models\User::where('role', 'bakery_manager')->first();
        if ($bakeryManager) {
            $bakeryManager->notify(new OrderPlaced($order));
        }
        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'order_created',
            'subject_type' => Order::class,
            'subject_id' => $order->id,
            'description' => 'Order #' . $order->id . ' created.'
        ]);
        return redirect()->route('retail.orders.show', $order->id)->with('success', 'Order created!');
    }

    // Show order details
    public function show($id)
    {
        $order = Order::with('items', 'payment')->findOrFail($id);
        return view('retail.orders.show', compact('order'));
    }

    // Show edit form
    public function edit($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('retail.orders.edit', compact('order'));
    }

    // Update order and items
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($request->only([
            'status', 'total', 'payment_status', 'shipping_address', 'billing_address', 'placed_at', 'delivered_at'
        ]));
        // Update order items logic here
        return redirect()->route('retail.orders.show', $order->id)->with('success', 'Order updated!');
    }

    // Cancel/delete order
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('retail.orders.index')->with('success', 'Order deleted!');
    }

    // Change order status
    public function changeStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $newStatus = $request->input('status');
        $order->setStatus($newStatus);

        // Adjust inventory only when order is received, completed, or delivered
        if (in_array($newStatus, ['received', 'completed', 'delivered'])) {
            foreach ($order->items as $item) {
                // Deduct using inventory_id
                $inventory = isset($item->inventory_id) ? \App\Models\Inventory::find($item->inventory_id) : null;
                \Log::info('Inventory deduction attempt', [
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'item_inventory_id' => $item->inventory_id ?? null,
                    'inventory_found' => $inventory ? $inventory->id : null,
                    'inventory_quantity_before' => $inventory ? $inventory->quantity : null,
                    'item_quantity' => $item->quantity,
                ]);
                if ($inventory && $inventory->quantity >= $item->quantity) {
                    $inventory->quantity -= $item->quantity;
                    $inventory->save();
                }
            }
        }

        // Notify customer
        if ($order->user) {
            $order->user->notify(new OrderStatusUpdated($order));
        }
        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'order_status_changed',
            'subject_type' => Order::class,
            'subject_id' => $order->id,
            'description' => 'Order #' . $order->id . ' status changed to ' . $order->status
        ]);
        return redirect()->route('retail.orders.show', $order->id)->with('success', 'Order status updated!');
    }

    // Record payment for an order
    public function recordPayment(Request $request, Order $order)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
        ]);

        $order->payment()->create([
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Payment recorded successfully!');
    }

    // Handle order return request
    public function return(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);
        $order = Order::findOrFail($id);
        // You can add logic to mark the order as 'return_requested', log the reason, notify admin, etc.
        $order->update(['status' => 'return_requested']);
        // Optionally, store the reason in a separate table or as a note
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'order_return_requested',
            'subject_type' => Order::class,
            'subject_id' => $order->id,
            'description' => 'Return requested: ' . $request->reason,
        ]);
        return redirect()->route('retail.orders.show', $order->id)->with('success', 'Return request submitted successfully!');
    }
}
