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
        $orders = Order::query();
        // Add search/filter logic here
        $orders = $orders->latest()->paginate(20);
        return view('retail.orders.index', compact('orders'));
    }

    // Show order creation form
    public function create()
    {
        $products = Product::all()->map(function($product) {
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
            'vendor_id' => 1, // TODO: Replace with actual vendor selection logic
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
        // Create order items and update inventory
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $item['total_price'] = $item['quantity'] * $item['unit_price'];
                // Inventory check and deduction
                if (isset($item['inventory_id'])) {
                    $inventory = \App\Models\Inventory::find($item['inventory_id']);
                    if ($inventory && $inventory->quantity >= $item['quantity']) {
                        $inventory->quantity -= $item['quantity'];
                        $inventory->save();
                    } else {
                        return redirect()->back()->withErrors(['items' => 'Insufficient inventory for ' . ($item['product_name'] ?? 'product')])->withInput();
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
        $order->setStatus($request->input('status'));
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
} 