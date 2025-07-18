<?php

namespace App\Http\Controllers\Bakery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupplierOrder;
use App\Models\RetailerOrder;
use App\Notifications\SupplierOrderPlaced;
use App\Notifications\RetailerOrderReceived;
use App\Models\User;
use App\Models\Product;

class OrderProcessingController extends Controller
{
    public function index()
    {
        $suppliers = User::where('role', 'supplier')->get();
        $products = Product::where('type', 'raw_material')->get();

        $retailerOrders = \App\Models\Order::whereHas('user', function($q) {
                $q->where('role', 'retail_manager');
            })
            ->whereHas('items.product', function($q) {
                $q->where('type', 'finished_product');
            })
            ->whereIn('status', ['pending', 'processing']) // Add status filter
            ->with([
                'user',
                'items' => function($q) {
                    $q->whereHas('product', function($q2) {
                        $q2->where('type', 'finished_product');
                    });
                },
                'items.product'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('bakery.order-processing', compact('suppliers', 'products', 'retailerOrders'));
    }

    // AJAX: Store a new supplier order
    public function storeSupplierOrder(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'supplier_id' => 'required|exists:users,id',
        ]);
        $order = SupplierOrder::create([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'supplier_id' => $validated['supplier_id'],
            'status' => 'pending',
        ]);
        // Notify the supplier
        $supplier = User::find($validated['supplier_id']);
        if ($supplier) {
            $supplier->notify(new SupplierOrderPlaced($order));
        }
        return response()->json(['success' => true, 'order' => $order]);
    }

    // Handles form submission for placing an order to a supplier
    public function placeSupplierOrder(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'supplier_id' => 'required|exists:users,id',
        ]);

        // Find the vendor for the selected supplier
        $vendor = \App\Models\Vendor::where('user_id', $validated['supplier_id'])->first();
        if (!$vendor) {
            return response()->json(['success' => false, 'message' => 'Supplier vendor not found.'], 422);
        }

        $product = \App\Models\Product::find($validated['product_id']);
        $unitPrice = $product->unit_price ?? 0;
        $totalPrice = $unitPrice * $validated['quantity'];

        // Create the order
        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'vendor_id' => $vendor->id,
            'customer_name' => auth()->user()->name,
            'customer_email' => auth()->user()->email,
            'status' => 'pending',
            'total' => $totalPrice,
            'payment_status' => 'unpaid',
            'placed_at' => now(),
        ]);

        // Create the order item
        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => $validated['quantity'],
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
        ]);

        // Optionally notify the supplier here

        return response()->json(['success' => true, 'order' => $order]);
    }

    // AJAX: List all retailer orders (from orders table)
    public function listRetailerOrders()
    {
        $orders = \App\Models\Order::with(['user', 'items.product'])->orderBy('created_at', 'desc')->get();
        return response()->json(['orders' => $orders]);
    }

    // AJAX: Mark a retailer order as received
    public function receiveRetailerOrder($id)
    {
        $order = \App\Models\Order::with('items')->findOrFail($id);
        $order->status = 'received';
        $order->save();

        foreach ($order->items as $item) {
            // Find bakery inventory for this product
            $bakeryInventory = \App\Models\Inventory::where('product_id', $item->product_id)
                ->where('location', 'bakery')
                ->first();

            // Find or create retail inventory for this product
            $retailInventory = \App\Models\Inventory::firstOrCreate(
                [
                    'product_id' => $item->product_id,
                    'location' => 'retail',
                ],
                [
                    'item_name' => $item->product ? $item->product->name : $item->item_name,
                    'unit_price' => $bakeryInventory ? $bakeryInventory->unit_price : 0,
                ]
            );

            // Always sync name and price from bakery
            $retailInventory->item_name = $item->product ? $item->product->name : $item->item_name;
            $retailInventory->unit_price = $bakeryInventory ? $bakeryInventory->unit_price : $retailInventory->unit_price;
            $retailInventory->quantity += $item->quantity;
            $retailInventory->save();
        }

        // Notify the retailer
        if ($order->user) {
            $order->user->notify(new \App\Notifications\RetailerOrderReceived($order));
        }
        return response()->json(['success' => true, 'order' => $order]);
    }

    // AJAX: Update retailer order status
    public function updateRetailerOrderStatus(Request $request, $id)
    {
        $order = \App\Models\Order::with('items')->findOrFail($id);
        $newStatus = $request->input('status');
        $allowed = ['pending', 'processing', 'shipped', 'received'];
        if (!in_array($newStatus, $allowed)) {
            return response()->json(['success' => false, 'message' => 'Invalid status'], 400);
        }
        $order->status = $newStatus;
        $order->save();
        // If status is received, update retail inventory (call receiveRetailerOrder logic)
        if ($newStatus === 'received') {
            foreach ($order->items as $item) {
                // Find bakery inventory for this product
                $bakeryInventory = \App\Models\Inventory::where('product_id', $item->product_id)
                    ->where('location', 'bakery')
                    ->first();
                // Find or create retail inventory for this product
                $retailInventory = \App\Models\Inventory::firstOrCreate(
                    [
                        'product_id' => $item->product_id,
                        'location' => 'retail',
                    ],
                    [
                        'item_name' => $item->product ? $item->product->name : $item->item_name,
                        'unit_price' => $bakeryInventory ? $bakeryInventory->unit_price : 0,
                    ]
                );
                // Always sync name and price from bakery
                $retailInventory->item_name = $item->product ? $item->product->name : $item->item_name;
                $retailInventory->unit_price = $bakeryInventory ? $bakeryInventory->unit_price : $retailInventory->unit_price;
                $retailInventory->quantity += $item->quantity;
                $retailInventory->save();
            }
            // Notify the retailer
            if ($order->user) {
                $order->user->notify(new \App\Notifications\RetailerOrderReceived($order));
            }
        }
        return response()->json(['success' => true, 'order' => $order]);
    }

    public function listSupplierOrders()
    {
        $orders = \App\Models\Order::where('user_id', auth()->id())
            ->with(['items', 'vendor'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json(['orders' => $orders]);
    }
}
