<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class OrderController extends Controller
{
    // Show order placement form
    public function create()
    {
        $inventory = \App\Models\Inventory::where('location', 'retail')
            ->where('item_type', 'finished_good')
            ->where('quantity', '>', 0)
            ->get();

        return view('customer.order.create', compact('inventory'));

    }

    // Handle order submission
    public function store(Request $request)
    {
        // If 'items' is a JSON string, decode it
        if (is_string($request->items)) {
            $request->merge(['items' => json_decode($request->items, true) ?: []]);
        }

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
            $inventory = Inventory::findOrFail($item['id']);
            $product = \App\Models\Product::where('name', $inventory->item_name)->first();
            $unit_price = $product ? $product->unit_price : 0;
            $qty = min($item['quantity'], $inventory->quantity);
            $total += $qty * $unit_price;
            $orderItems[] = [
                'product_name' => $inventory->item_name,
                'quantity' => $qty,
                'unit_price' => $unit_price,
                'total_price' => $qty * $unit_price,
            ];
        }
        $order = Order::create([
            'user_id' => $user->id,
            'vendor_id' => 1,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'status' => 'pending',
            'total' => $total,
        ]);
        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }
        return redirect()->route('customer.orders.index')->with('success', 'Order placed successfully!');
    }

    /**
     * Display customer's order history.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items'])
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Show a specific order.
     */
    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated customer
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['items']);

        return view('customer.orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        // Ensure the order belongs to the authenticated customer
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        $order->status = 'cancelled';
        $order->save();
        return redirect()->route('customer.orders.index')->with('success', 'Order cancelled successfully!');
    }
}
