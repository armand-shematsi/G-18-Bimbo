<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('customer_cart', []);
        $total = collect($cart)->sum('total_price');
        return view('customer.cart.index', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        \Log::info('Customer CartController@store called', ['user' => auth()->user()]);
        $cart = Session::get('customer_cart', []);
        $inventoryId = $request->input('inventory_id');
        $quantity = $request->input('quantity');
        $inventory = Inventory::find($inventoryId);
        if (!$inventory || $inventory->quantity < $quantity) {
            return redirect()->back()->with('error', 'Product is out of stock or insufficient quantity.');
        }
        $product = Product::where('name', $inventory->item_name)->first();
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }
        $item = [
            'product_id' => $product->id,
            'product_name' => $inventory->item_name,
            'quantity' => $quantity,
            'unit_price' => $product->unit_price,
            'total_price' => $quantity * $product->unit_price,
            'inventory_id' => $inventory->id,
        ];
        $cart[] = $item;
        Session::put('customer_cart', $cart);
        return redirect()->route('customer.cart.index')->with('success', 'Item added to cart!');
    }

    public function update(Request $request, $id)
    {
        $cart = Session::get('customer_cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            $cart[$id]['total_price'] = $cart[$id]['quantity'] * $cart[$id]['unit_price'];
            Session::put('customer_cart', $cart);
            return redirect()->route('customer.cart.index')->with('success', 'Cart updated successfully!');
        }
        return redirect()->route('customer.cart.index')->with('error', 'Item not found in cart!');
    }

    public function destroy($id)
    {
        $cart = Session::get('customer_cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('customer_cart', array_values($cart));
            return redirect()->route('customer.cart.index')->with('success', 'Item removed from cart!');
        }
        return redirect()->route('customer.cart.index')->with('error', 'Item not found in cart!');
    }

    public function checkout()
    {
        $cart = Session::get('customer_cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.cart.index')->with('error', 'Cart is empty!');
        }
        $total = collect($cart)->sum('total_price');
        return view('customer.cart.checkout', compact('cart', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $cart = Session::get('customer_cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.cart.index')->with('error', 'Cart is empty!');
        }
        $user = Auth::user();
        $total = collect($cart)->sum('total_price');
        $order = Order::create([
            'user_id' => $user->id,
            'vendor_id' => 1, // TODO: Replace with actual vendor selection logic
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'status' => 'pending',
            'total' => $total,
        ]);
        foreach ($cart as $item) {
            $order->items()->create($item);
            // Deduct inventory
            $inventory = Inventory::find($item['inventory_id']);
            if ($inventory && $inventory->quantity >= $item['quantity']) {
                $inventory->quantity -= $item['quantity'];
                $inventory->save();
            }
        }
        Session::forget('customer_cart');
        return redirect()->route('customer.orders.index')->with('success', 'Order placed successfully!');
    }
} 