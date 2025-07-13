<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class RawMaterialOrderController extends Controller
{
    // Show catalog of raw materials from other suppliers
    public function catalog()
    {
        $supplierId = Auth::id();
        $rawMaterials = Inventory::whereHas('user', function($query) use ($supplierId) {
            $query->where('role', 'supplier')->where('id', '!=', $supplierId);
        })->where('status', 'available')->get();
        return view('supplier.raw-materials.catalog', compact('rawMaterials'));
    }

    // Add item to cart
    public function addToCart(Request $request)
    {
        $cart = Session::get('supplier_cart', []);
        $inventoryId = $request->input('inventory_id');
        $quantity = $request->input('quantity');
        $inventory = Inventory::find($inventoryId);
        if (!$inventory || $inventory->quantity < $quantity) {
            return redirect()->back()->with('error', 'Insufficient stock for this raw material.');
        }
        $item = [
            'inventory_id' => $inventory->id,
            'product_name' => $inventory->item_name,
            'supplier_id' => $inventory->user_id,
            'quantity' => $quantity,
            'unit_price' => $inventory->unit_price,
            'total_price' => $quantity * $inventory->unit_price,
        ];
        $cart[] = $item;
        Session::put('supplier_cart', $cart);
        return redirect()->route('supplier.raw-materials.cart')->with('success', 'Item added to cart!');
    }

    // Show cart
    public function cart()
    {
        $cart = Session::get('supplier_cart', []);
        $total = collect($cart)->sum('total_price');
        return view('supplier.raw-materials.cart', compact('cart', 'total'));
    }

    // Remove item from cart
    public function removeFromCart($index)
    {
        $cart = Session::get('supplier_cart', []);
        if (isset($cart[$index])) {
            unset($cart[$index]);
            Session::put('supplier_cart', array_values($cart));
        }
        return redirect()->route('supplier.raw-materials.cart')->with('success', 'Item removed from cart!');
    }

    // Checkout and place order
    public function checkout(Request $request)
    {
        $cart = Session::get('supplier_cart', []);
        if (empty($cart)) {
            return redirect()->route('supplier.raw-materials.cart')->with('error', 'Cart is empty!');
        }
        $total = collect($cart)->sum('total_price');
        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
            'total' => $total,
            'payment_status' => 'unpaid',
            'shipping_address' => $request->input('shipping_address'),
            'billing_address' => $request->input('billing_address'),
            'placed_at' => now(),
        ]);
        // Create order items and update inventory
        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => null,
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
                'supplier_id' => $item['supplier_id'],
            ]);
            $inventory = Inventory::find($item['inventory_id']);
            if ($inventory) {
                $inventory->quantity -= $item['quantity'];
                $inventory->save();
            }
        }
        Session::forget('supplier_cart');
        return redirect()->route('supplier.orders.show', $order->id)->with('success', 'Raw material order placed!');
    }
} 