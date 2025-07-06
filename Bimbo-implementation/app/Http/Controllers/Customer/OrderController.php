<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the customer's orders.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
                      ->with(['items'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Show order placement form
     */
    public function create()
    {
        $products = Inventory::where('status', 'available')->where('quantity', '>', 0)->get();
        return view('customer.order.create', compact('products'));
    }

    /**
     * Handle order submission
     */
    public function store(Request $request)
    {
        // Filter out items with quantity < 1
        $items = collect($request->input('items', []))
            ->filter(function($item) {
                return isset($item['quantity']) && $item['quantity'] > 0;
            })->values();

        if ($items->isEmpty()) {
            return back()->withErrors(['items' => 'Please select at least one product and quantity.'])->withInput();
        }

        $request->merge(['items' => $items->toArray()]);

        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:inventories,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $total = 0;
        $orderItems = [];
        foreach ($items as $item) {
            $product = Inventory::findOrFail($item['id']);
            $qty = min($item['quantity'], $product->quantity);
            $total += $qty * $product->unit_price;
            $orderItems[] = [
                'product_name' => $product->item_name,
                'quantity' => $qty,
                'unit_price' => $product->unit_price,
                'total_price' => $qty * $product->unit_price,
            ];
        }
        $order = Order::create([
            'user_id' => $user->id,
            'vendor_id' => 1,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'status' => 'pending',
            'total_amount' => $total,
        ]);
        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }
        return redirect()->route('dashboard')->with('success', 'Order placed successfully!');
    }
}
