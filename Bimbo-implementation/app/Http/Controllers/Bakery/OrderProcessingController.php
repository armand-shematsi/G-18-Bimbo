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
        // Fetch raw materials from supplier inventory
        $rawMaterials = \App\Models\Inventory::where('location', 'supplier')
            ->where('item_type', 'raw_material')
            ->get();
        $products = Product::where('type', 'raw_material')->get(); // keep for compatibility

        $retailerOrders = \App\Models\Order::whereHas('user', function($q) {
                $q->where('role', 'retail_manager');
            })
            ->with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        $supplierOrders = \App\Models\SupplierOrder::with('product')->orderBy('created_at', 'desc')->get();
        return view('bakery.order-processing', compact('suppliers', 'products', 'rawMaterials', 'retailerOrders', 'supplierOrders'));
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

        $order = \App\Models\SupplierOrder::create([
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

    // AJAX: List all retailer orders (from orders table)
    public function listRetailerOrders()
    {
        $orders = \App\Models\Order::whereHas('user', function($q) {
                $q->where('role', 'retail_manager');
            })
            ->whereDoesntHave('items.product', function($q) {
                $q->where('type', 'raw_material');
            })
            ->whereIn('status', ['pending', 'processing'])
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
        return response()->json(['orders' => $orders]);
    }

    // AJAX: Mark a retailer order as received
    public function receiveRetailerOrder($id)
    {
        $order = \App\Models\Order::with('items')->findOrFail($id);
        $order->status = 'received';
        $order->save();

        // Sync status to RetailerOrder
        foreach ($order->items as $item) {
            \App\Models\RetailerOrder::where('retailer_id', $order->user_id)
                ->where('product_id', $item->product_id)
                ->update(['status' => $order->status]);
        }

        foreach ($order->items as $item) {
            $productId = (int)($item->product_id ?? ($item->product ? $item->product->id : null));
            $location = trim(strtolower('retail'));
            if (!$productId || !$location) {
                \Log::warning('Skipping inventory update: missing product_id for order item', [
                    'order_id' => $order->id,
                    'item_id' => $item->id ?? null,
                    'item_name' => $item->item_name ?? null,
                ]);
                continue;
            }
            $retailInventory = \App\Models\Inventory::where('product_id', $productId)
                ->where('location', $location)
                ->first();
            if (!$retailInventory) {
                \Log::warning('No existing retail inventory found for product_id', [
                    'product_id' => $productId,
                    'location' => $location,
                    'order_id' => $order->id,
                    'item_id' => $item->id ?? null,
                    'item_name' => $item->item_name ?? null,
                ]);
                continue;
            }
            // Always sync name and price from bakery
            $bakeryInventory = \App\Models\Inventory::where('product_id', $productId)
                ->where('location', 'bakery')
                ->first();
            \Log::info('Retail inventory adjustment', [
                'product_id' => $productId,
                'item_name' => $retailInventory->item_name,
                'current_quantity' => $retailInventory->quantity,
                'adjustment' => $item->quantity,
                'new_quantity' => $retailInventory->quantity + $item->quantity,
                'order_id' => $order->id,
                'item_id' => $item->id ?? null,
            ]);
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
        \Log::info('updateRetailerOrderStatus called', ['order_id' => $id, 'request' => $request->all()]);
        $order = \App\Models\Order::with('items')->findOrFail($id);
        $newStatus = $request->input('status');
        $allowed = ['pending', 'processing', 'shipped', 'received'];
        if (!in_array($newStatus, $allowed)) {
            return response()->json(['success' => false, 'message' => 'Invalid status'], 400);
        }
        $order->status = $newStatus;
        $order->save();
        // Sync status to RetailerOrder
        foreach ($order->items as $item) {
            \App\Models\RetailerOrder::where('retailer_id', $order->user_id)
                ->where('product_id', $item->product_id)
                ->update(['status' => $order->status]);
        }
        // If status is processing, shipped, or received, update retail inventory
        if (in_array($newStatus, ['processing', 'shipped', 'received'])) {
            foreach ($order->items as $item) {
                $productId = (int)($item->product_id ?? ($item->product ? $item->product->id : null));
                $location = trim(strtolower('retail'));
                if (!$productId || !$location) {
                    \Log::warning('Skipping inventory update: missing product_id for order item', [
                        'order_id' => $order->id,
                        'item_id' => $item->id ?? null,
                        'item_name' => $item->item_name ?? null,
                    ]);
                    continue;
                }
                $retailInventory = \App\Models\Inventory::where('product_id', $productId)
                    ->where('location', $location)
                    ->first();
                if (!$retailInventory) {
                    \Log::warning('No existing retail inventory found for product_id', [
                        'product_id' => $productId,
                        'location' => $location,
                        'order_id' => $order->id,
                        'item_id' => $item->id ?? null,
                        'item_name' => $item->item_name ?? null,
                    ]);
                    continue;
                }
                // Always sync name and price from bakery
                $bakeryInventory = \App\Models\Inventory::where('product_id', $productId)
                    ->where('location', 'bakery')
                    ->first();
                \Log::info('Retail inventory adjustment', [
                    'product_id' => $productId,
                    'item_name' => $retailInventory->item_name,
                    'current_quantity' => $retailInventory->quantity,
                    'adjustment' => $item->quantity,
                    'new_quantity' => $retailInventory->quantity + $item->quantity,
                    'order_id' => $order->id,
                    'item_id' => $item->id ?? null,
                ]);
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
