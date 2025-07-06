<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupplierOrder;
use App\Models\Product;

class SupplierOrderController extends Controller
{
    // List all orders for the logged-in supplier
    public function index()
    {
        $orders = SupplierOrder::where('supplier_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view('dashboard.supplier', compact('orders'));
    }

    // Update order status
    public function update(Request $request, $id)
    {
        $order = SupplierOrder::where('supplier_id', auth()->id())->findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:pending,accepted,rejected,shipped',
        ]);
        $order->status = $validated['status'];
        $order->save();
        return redirect()->back()->with('success', 'Order status updated!');
    }
}
