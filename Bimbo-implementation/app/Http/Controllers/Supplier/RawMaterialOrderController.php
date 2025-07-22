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
        $user = Auth::user();
        if ($user->role !== 'bakery_manager') {
            abort(403, 'Only bakery managers can order raw materials from suppliers.');
        }
        $cart = Session::get('supplier_cart', []);
        if (empty($cart)) {
            return redirect()->route('supplier.raw-materials.cart')->with('error', 'Cart is empty!');
        }
        try {
            $validated = $request->validate([
                'shipping_address' => 'required|string',
                'billing_address' => 'required|string',
            ]);
            $total = collect($cart)->sum('total_price');
            $customerName = $user->name ?? 'Bakery Manager';
            $messages = [];
            foreach ($cart as $item) {
                // Find or create vendor for supplier
                $vendor = \App\Models\Vendor::firstOrCreate(
                    ['user_id' => $item['supplier_id']],
                    [
                        'name' => $user->name ?? 'Supplier',
                        'email' => $user->email ?? 'supplier@example.com',
                        'phone' => $user->phone ?? '000-000-0000',
                        'address' => $user->address ?? 'Unknown Address',
                        'city' => $user->city ?? 'Unknown City',
                        'state' => $user->state ?? 'Unknown State',
                        'zip_code' => $user->zip_code ?? '00000',
                        'business_type' => 'Supplier',
                        'tax_id' => $user->tax_id ?? 'TAX000000',
                        'business_license' => $user->business_license ?? 'LIC000000',
                        'status' => 'active',
                        'sales' => 0,
                        'annual_revenue' => 0,
                        'years_in_business' => 0,
                        'regulatory_certification' => $user->regulatory_certification ?? null,
                    ]
                );
                $vendorId = $vendor->id;
                // Create order for this vendor
                $order = Order::create([
                    'user_id' => $user->id,
                    'vendor_id' => $vendorId,
                    'customer_name' => $customerName,
                    'status' => 'pending',
                    'total' => $item['total_price'],
                    'payment_status' => 'unpaid',
                    'shipping_address' => $request->input('shipping_address'),
                    'billing_address' => $request->input('billing_address'),
                    'placed_at' => now(),
                ]);
                // Check inventory
                $inventory = Inventory::find($item['inventory_id']);
                if ($inventory && $inventory->quantity >= $item['quantity']) {
                    $order->items()->create([
                        'product_id' => $item['product_id'] ?? null,
                        'product_name' => $item['product_name'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total_price' => $item['total_price'],
                        'supplier_id' => $item['supplier_id'],
                    ]);
                    $inventory->quantity -= $item['quantity'];
                    $inventory->save();
                } else {
                    $messages[] = 'Insufficient stock for ' . $item['product_name'] . '. Order not placed for this item.';
                    continue;
                }
                $messages[] = 'Order placed for ' . $item['product_name'] . ' (Qty: ' . $item['quantity'] . ').';
            }
            // Add SupplierOrder creation for each cart item
            foreach ($cart as $item) {
                \App\Models\SupplierOrder::create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'supplier_id' => $item['supplier_id'],
                    'status' => 'pending',
                ]);
            }
            Session::forget('supplier_cart');
            $finalMsg = implode(' ', $messages);
            return redirect()->route('supplier.raw-materials.cart')->with('success', 'Order process complete. ' . $finalMsg);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('supplier.raw-materials.cart')->with('error', 'Validation failed: ' . implode(' ', $e->errors()));
        } catch (\Throwable $e) {
            return redirect()->route('supplier.raw-materials.cart')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
