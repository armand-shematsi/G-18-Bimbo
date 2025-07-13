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
        $cart = Session::get('cart', []);
        $total = collect($cart)->sum('total_price');
        return view('retail.cart.index', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $cart = Session::get('cart', []);
        $inventoryId = $request->input('inventory_id');
        $quantity = $request->input('quantity');

        // Fetch inventory and product
        $inventory = \App\Models\Inventory::where('id', $inventoryId)->first();
        if (!$inventory || $inventory->quantity < $quantity) {
            return redirect()->back()->with('error', 'Product is out of stock or insufficient quantity.');
        }

        // Fetch product price
        $product = \App\Models\Product::where('name', $inventory->item_name)->first();
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $item = [
            'product_id' => $product->id,
            'product_name' => $inventory->item_name,
            'quantity' => $quantity,
            'unit_price' => $product->price,
            'total_price' => $quantity * $product->price,
        ];

        $cart[] = $item;
        Session::put('cart', $cart);

        return redirect()->route('retail.cart.index')->with('success', 'Item added to cart!');
    }

    public function update(Request $request, $id)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            $cart[$id]['total_price'] = $cart[$id]['quantity'] * $cart[$id]['unit_price'];
            Session::put('cart', $cart);
            return redirect()->route('retail.cart.index')->with('success', 'Cart updated successfully!');
        }
        
        return redirect()->route('retail.cart.index')->with('error', 'Item not found in cart!');
    }

    public function destroy($id)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
            return redirect()->route('retail.cart.index')->with('success', 'Item removed from cart!');
        }
        
        return redirect()->route('retail.cart.index')->with('error', 'Item not found in cart!');
    }

    public function checkout(Request $request)
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('retail.cart.index')->with('error', 'Cart is empty!');
        }
        
        $total = collect($cart)->sum('total_price');
        return view('retail.cart.checkout', compact('cart', 'total'));
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