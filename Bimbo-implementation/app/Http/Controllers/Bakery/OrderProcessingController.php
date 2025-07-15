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
        $products = Product::all();
        $retailerOrders = \App\Models\Order::orderBy('created_at', 'desc')->get();
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

    // AJAX: List all retailer orders (now from orders table)
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
            $inventory = \App\Models\Inventory::where('product_id', $item->product_id)
                ->orWhere('item_name', 'like', '%' . $item->item_name . '%')
                ->first();

            if ($inventory) {
                $inventory->quantity += $item->quantity; // INCREASE inventory on delivery
                $inventory->save();

                $inventory->movements()->create([
                    'quantity' => $item->quantity,
                    'type' => 'in',
                    'note' => 'Retailer Order #' . $order->id . ' delivered to retail',
                    'user_id' => auth()->id(),
                ]);
            }
        }

        // Notify the retailer
        if ($order->retailer) {
            $order->retailer->notify(new \App\Notifications\RetailerOrderReceived($order));
        }
        return response()->json(['success' => true, 'order' => $order]);
    }

    // AJAX: Update retailer order status
    public function updateRetailerOrderStatus(Request $request, $id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $newStatus = $request->input('status');
        $allowed = ['pending', 'processing', 'shipped', 'received'];
        if (!in_array($newStatus, $allowed)) {
            return response()->json(['success' => false, 'message' => 'Invalid status'], 400);
        }
        $order->status = $newStatus;
        $order->save();
        // Optionally: notify retailer, update inventory, etc.
        return response()->json(['success' => true, 'order' => $order]);
    }
}
