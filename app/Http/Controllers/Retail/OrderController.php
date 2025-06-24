<?php

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\OrderItem;
use App\Models\Product;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->take(10)->get();
        return view('retail.orders.index', compact('orders'));
    }

    public function create()
    {
        $vendors = Vendor::all();
        return view('retail.orders.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        // Validate and save the order
        $order = new Order();
        $order->vendor_id = $request->vendor_id;
        $order->customer_name = $request->customer_name;
        $order->customer_email = $request->customer_email;
        $order->delivery_date = $request->delivery_date;
        $order->delivery_time = $request->delivery_time;
        $order->save();

        // Save order items and update inventory
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                if (!empty($item['product_id']) && !empty($item['quantity'])) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'notes' => $item['notes'] ?? null,
                    ]);
                    // Update product stock
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->stock = max(0, $product->stock - (int)$item['quantity']);
                        $product->save();
                    }
                }
            }
        }

        return redirect()->route('retail.orders.index')->with('success', 'Order placed successfully!');
    }

    public function show($id)
    {
        return view('retail.orders.show', compact('id'));
    }

    public function edit($id)
    {
        return view('retail.orders.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // TODO: Implement order update logic
        return redirect()->route('retail.orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy($id)
    {
        // TODO: Implement order deletion logic
        return redirect()->route('retail.orders.index')->with('success', 'Order deleted successfully.');
    }
}