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
        $user = Auth::user();
        if ($user->role !== 'bakery_manager') {
            abort(403, 'Only bakery managers can order raw materials from suppliers.');
        }
        // Existing logic for suppliers/bakery managers
        $supplierId = $user->id;
        $rawMaterials = \App\Models\Inventory::whereHas('user', function($query) use ($supplierId) {
            $query->where('role', 'supplier')->where('id', '!=', $supplierId);
        })->where('status', 'available')->where('item_type', 'raw_material')->with('product')->get();
        // Only keep unique raw materials by item_name and supplier (user_id)
        $uniqueRawMaterials = $rawMaterials->unique(function ($item) {
            return $item->item_name . '-' . $item->user_id;
        })->values();
        return view('supplier.raw-materials.catalog', ['rawMaterials' => $uniqueRawMaterials]);
    }

    // Add item to cart
    public function addToCart(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'bakery_manager') {
            abort(403, 'Only bakery managers can order raw materials from suppliers.');
        }
        $cart = Session::get('supplier_cart', []);
        $inventoryId = $request->input('inventory_id');
        $quantity = $request->input('quantity');
        $inventory = Inventory::find($inventoryId);
        if (!$inventory || $inventory->quantity < $quantity) {
            return redirect()->back()->with('error', 'Insufficient stock for this raw material.');
        }
        $item = [
            'inventory_id' => $inventory->id,
            'product_id' => $inventory->product_id, // Store product_id
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
        $user = Auth::user();
        if ($user->role !== 'bakery_manager') {
            abort(403, 'Only bakery managers can order raw materials from suppliers.');
        }
        $cart = Session::get('supplier_cart', []);
        $total = collect($cart)->sum('total_price');
        $user = Auth::user();
        $customerName = $user ? $user->name : '';
        $customerEmail = $user ? $user->email : '';
        $defaultAddress = $user && property_exists($user, 'address') ? $user->address : 'Bakery HQ, Main Street';
        return view('supplier.raw-materials.cart', compact('cart', 'total', 'customerName', 'customerEmail', 'defaultAddress'));
    }

    // Remove item from cart
    public function removeFromCart($index)
    {
        $user = Auth::user();
        if ($user->role !== 'bakery_manager') {
            abort(403, 'Only bakery managers can order raw materials from suppliers.');
        }
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
        \Log::info('CHECKOUT DEBUG', ['user_id' => \Auth::id(), 'role' => \Auth::user()->role]);
        $user = Auth::user();
        if ($user->role !== 'bakery_manager') {
            abort(403, 'Only bakery managers can order raw materials from suppliers.');
        }
        \Log::info('CHECKOUT METHOD CALLED', [
            'user_id' => Auth::id(),
            'cart' => Session::get('supplier_cart', []),
            'request' => $request->all()
        ]);
        try {
            // Add logging before validation
            \Log::info('Before validation', ['request' => $request->all()]);
            $validated = $request->validate([
                'shipping_address' => 'required|string',
                'billing_address' => 'required|string',
            ]);
            \Log::info('Validation passed', ['validated' => $validated]);
            $cart = Session::get('supplier_cart', []);
            if (empty($cart)) {
                \Log::warning('Cart is empty during checkout', ['user_id' => Auth::id()]);
                return redirect()->route('supplier.raw-materials.cart')->with('error', 'Cart is empty!');
            }
            $total = collect($cart)->sum('total_price');
            $customerName = Auth::user() ? Auth::user()->name : 'Bakery Manager';
            $vendor = \App\Models\Vendor::where('user_id', $cart[0]['supplier_id'])->first();
            $vendorId = $vendor ? $vendor->id : null;
            if (!$vendorId) {
                \Log::warning('No vendor found for supplier', ['supplier_id' => $cart[0]['supplier_id']]);
                return redirect()->route('supplier.raw-materials.cart')->with('error', 'Vendor not found!');
            }
            $order = Order::create([
                'user_id' => Auth::id(),
                'vendor_id' => $vendorId,
                'customer_name' => $customerName,
                'status' => 'pending',
                'total' => $total,
                'payment_status' => 'unpaid',
                'shipping_address' => $request->input('shipping_address'),
                'billing_address' => $request->input('billing_address'),
                'placed_at' => now(),
            ]);
            \Log::info('Raw material order created', [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'vendor_id' => $order->vendor_id,
                'customer_name' => $order->customer_name
            ]);
            \Log::info('Order created', ['order_id' => $order->id]);
            foreach ($cart as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'] ?? null,
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
            \Log::info('Redirecting to supplier.orders.show', ['order_id' => $order->id]);
            // Redirect bakery manager to cart with success message instead of supplier order details
            return redirect()->route('supplier.raw-materials.cart')->with('success', 'Raw material order placed successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed in checkout', [
                'errors' => $e->errors(),
                'request' => $request->all(),
            ]);
            throw $e; // Let Laravel handle the redirect and error display
        } catch (\Throwable $e) {
            \Log::error('Error placing raw material order', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('supplier.raw-materials.cart')->with('error', 'An error occurred while placing the order. Please try again.');
        }
    }
}