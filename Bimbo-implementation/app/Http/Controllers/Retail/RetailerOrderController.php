<?php

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RetailerOrder;
use App\Models\Product;

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
            $order->items()->create([
                'product_id' => $item['product_id'],
                'item_name' => $product ? $product->name : null,
                'quantity' => $item['quantity'],
            ]);
        }
        return redirect()->back()->with('success', 'Order placed successfully!');
    }
}
