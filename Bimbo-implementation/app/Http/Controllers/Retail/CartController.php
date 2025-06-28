<?php

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Inventory;

class CartController extends Controller
{
    public function index()
    {
        // Implement the logic to display the cart page
    }

    public function store(Request $request)
    {
        $cart = Session::get('cart', []);
        $item = $request->only(['product_id', 'product_name', 'quantity', 'unit_price']);
        $inventory = Inventory::where('id', $item['product_id'])->first();
        if (!$inventory || $inventory->quantity < $item['quantity']) {
            return redirect()->back()->with('error', 'Product is out of stock or insufficient quantity.');
        }
        $item['total_price'] = $item['quantity'] * $item['unit_price'];
        $cart[] = $item;
        Session::put('cart', $cart);
        return redirect()->route('retail.cart.index')->with('success', 'Item added to cart!');
    }

    public function update(Request $request, $id)
    {
        // Implement the logic to update an item in the cart
    }

    public function destroy($id)
    {
        // Implement the logic to remove an item from the cart
    }

    public function checkout(Request $request)
    {
        // Implement the logic to display the checkout page
    }

    public function placeOrder(Request $request)
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('retail.cart.index')->with('error', 'Cart is empty!');
        }
        // Deduct inventory
        foreach ($cart as $item) {
            $inventory = Inventory::where('id', $item['product_id'])->first();
            if (!$inventory || $inventory->quantity < $item['quantity']) {
                return redirect()->route('retail.cart.index')->with('error', 'Product ' . $item['product_name'] . ' is out of stock or insufficient quantity.');
            }
            $inventory->quantity -= $item['quantity'];
            $inventory->save();
        }
        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'total' => collect($cart)->sum('total_price'),
            'payment_status' => 'unpaid',
            'shipping_address' => $request->input('shipping_address'),
            'billing_address' => $request->input('billing_address'),
            'placed_at' => now(),
        ]);
        // Create order items
        foreach ($cart as $item) {
            $order->items()->create($item);
        }
        // Create payment record
        $order->payment()->create([
            'user_id' => auth()->id(),
            'amount' => $order->total,
            'payment_method' => $request->input('payment_method'),
            'status' => 'pending',
        ]);
        Session::forget('cart');
        return redirect()->route('retail.orders.show', $order->id)->with('success', 'Order placed!');
    }
} 