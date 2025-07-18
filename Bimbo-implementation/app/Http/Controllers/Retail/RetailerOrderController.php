<?php

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RetailerOrder;
use App\Models\Product;
use App\Models\Order;

class RetailerOrderController extends Controller
{
    // Show form and list of orders
    public function index()
    {
        $products = Product::all();
        $orders = RetailerOrder::where('retailer_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view('dashboard.retail-manager', compact('products', 'orders'));
    }

    // Store a new retailer order
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
        $order = RetailerOrder::create([
            'retailer_id' => auth()->id(),
            'status' => 'pending',
        ]);
        foreach ($validated['items'] as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            $inventory = \App\Models\Inventory::where('product_id', $item['product_id'])->where('location', 'retail')->first();
            $unitPrice = $inventory ? $inventory->unit_price : ($product ? ($product->unit_price ?? $product->price ?? 0) : 0);
            $totalPrice = $unitPrice * $item['quantity'];
            $order->items()->create([
                'product_id' => $product->id,
                'item_name' => $product ? $product->name : null,
                'quantity' => $item['quantity'],
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
            ]);
        }
        return redirect()->back()->with('success', 'Order placed successfully!');
    }

    // Fulfill a customer order and deduct retail inventory
    public function fulfill($id)
    {
        $order = RetailerOrder::with('items')->findOrFail($id);
        $order->status = 'fulfilled';
        $order->save();

        foreach ($order->items as $item) {
            $inventory = \App\Models\Inventory::where('product_id', $item->product_id)
                ->where('location', 'retail')
                ->first();
            if ($inventory && $inventory->quantity >= $item->quantity) {
                $inventory->quantity -= $item->quantity;
                $inventory->save();
            }
        }
        return redirect()->back()->with('success', 'Order fulfilled and inventory updated.');
    }

    // Deliver a customer order and deduct retail inventory
    public function deliver($id)
    {
        $order = RetailerOrder::with('items')->findOrFail($id);
        $order->status = 'delivered';
        $order->save();

        foreach ($order->items as $item) {
            $inventory = \App\Models\Inventory::where('product_id', $item->product_id)
                ->where('location', 'retail')
                ->first();
            if ($inventory && $inventory->quantity >= $item->quantity) {
                $inventory->quantity -= $item->quantity;
                $inventory->save();
            }
        }
        return redirect()->back()->with('success', 'Order delivered and inventory updated.');
    }

    // Update order status and deduct inventory if delivered
    public function updateStatus(Request $request, $id)
    {
        $order = RetailerOrder::with('items')->findOrFail($id);
        $newStatus = $request->input('status');
        $order->status = $newStatus;
        $order->save();

        if ($newStatus === 'delivered') {
            foreach ($order->items as $item) {
                $inventory = \App\Models\Inventory::where('product_id', $item->product_id)
                    ->where('location', 'retail')
                    ->first();
                if ($inventory && $inventory->quantity >= $item->quantity) {
                    $inventory->quantity -= $item->quantity;
                    $inventory->save();
                }
            }
        }
        return redirect()->back()->with('success', 'Order status updated.');
    }
}
