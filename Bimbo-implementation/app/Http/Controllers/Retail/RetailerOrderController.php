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
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $order = RetailerOrder::create([
            'retailer_id' => auth()->id(),
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'status' => 'pending',
        ]);
        return redirect()->back()->with('success', 'Order placed successfully!');
    }
}
