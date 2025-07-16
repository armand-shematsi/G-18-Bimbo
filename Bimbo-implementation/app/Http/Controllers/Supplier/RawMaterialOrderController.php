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
        if ($user->role === 'retail_manager') {
            // Retail managers see all available raw materials from suppliers
            $rawMaterials = \App\Models\Inventory::whereHas('user', function($query) {
                $query->where('role', 'supplier');
            })->where('status', 'available')->where('item_type', 'raw_material')->get();
        } else {
            // Existing logic for suppliers/bakery managers
            $supplierId = $user->id;
            $rawMaterials = \App\Models\Inventory::whereHas('user', function($query) use ($supplierId) {
                $query->where('role', 'supplier')->where('id', '!=', $supplierId);
            })->where('status', 'available')->where('item_type', 'raw_material')->get();
        }
        // Only keep unique raw materials by item_name and supplier (user_id)
        $uniqueRawMaterials = $rawMaterials->unique(function ($item) {
            return $item->item_name . '-' . $item->user_id;
        })->values();
        return view('supplier.raw-materials.catalog', ['rawMaterials' => $uniqueRawMaterials]);
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
        $user = Auth::user();
        $customerName = $user ? $user->name : '';
        $customerEmail = $user ? $user->email : '';
        $defaultAddress = $user && property_exists($user, 'address') ? $user->address : 'Bakery HQ, Main Street';
        return view('supplier.raw-materials.cart', compact('cart', 'total', 'customerName', 'customerEmail', 'defaultAddress'));
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
        \Log::info('RawMaterialOrderController@checkout called', [
            'user_id' => Auth::id(),
            'request' => $request->all(),
            'session_cart' => Session::get('supplier_cart', [])
        ]);
        try {
            $cart = Session::get('supplier_cart', []);
            if (empty($cart)) {
                \Log::warning('Cart is empty during checkout', ['user_id' => Auth::id()]);
                return redirect()->route('supplier.raw-materials.cart')->with('error', 'Cart is empty!');
            }
            $total = collect($cart)->sum('total_price');
            // Ensure customer_name is always set
            $customerName = Auth::user() ? Auth::user()->name : 'Bakery Manager';
            // Find the vendor for the supplier user
            $vendor = \App\Models\Vendor::where('user_id', $cart[0]['supplier_id'])->first();
            $vendorId = $vendor ? $vendor->id : null;
            // Create order
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
            \Log::info('Order created', ['order_id' => $order->id]);
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
            \Log::info('Redirecting to supplier.orders.show', ['order_id' => $order->id]);
            return redirect()->route('supplier.orders.show', ['order' => $order->id])->with('success', 'Raw material order placed!');
        } catch (\Throwable $e) {
            \Log::error('Error placing raw material order', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('supplier.raw-materials.cart')->with('error', 'An error occurred while placing the order. Please try again.');
        }
    }
} 