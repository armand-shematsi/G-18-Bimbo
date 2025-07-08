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

    // AJAX: List all retailer orders
    public function listRetailerOrders()
    {
        $orders = RetailerOrder::with('retailer')->orderBy('created_at', 'desc')->get();
        return response()->json(['orders' => $orders]);
    }

    // AJAX: Mark a retailer order as received
    public function receiveRetailerOrder($id)
    {
        $order = RetailerOrder::findOrFail($id);
        $order->status = 'received';
        $order->save();
        // Notify the retailer
        if ($order->retailer) {
            $order->retailer->notify(new RetailerOrderReceived($order));
        }
        return response()->json(['success' => true, 'order' => $order]);
    }
}
