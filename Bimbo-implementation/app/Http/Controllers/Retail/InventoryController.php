<?php

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = Inventory::where('location', 'retail')->get();
        return view('retail.inventory.index', compact('inventory'));
    }

    // Show inventory and stock in/out form
    public function check()
    {
        // Only get items available in the retail shop
        $items = Inventory::where('location', 'retail')->get();
        return view('retail.inventory.check', compact('items'));
    }

    // Handle stock in/out update
    public function updateStock(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'required|exists:inventories,id',
            'action' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
        ]);
        $item = Inventory::findOrFail($data['item_id']);
        if ($data['action'] === 'in') {
            $item->quantity += $data['quantity'];
        } else {
            if ($item->quantity < $data['quantity']) {
                return back()->withErrors(['quantity' => 'Not enough stock to remove.']);
            }
            $item->quantity -= $data['quantity'];
        }
        $item->save();
        // Optionally: log movement here
        return redirect()->route('retail.inventory.check')->with('status', 'Stock updated successfully.');
    }

    // Show form to add new inventory item
    public function create()
    {
        return view('retail.inventory.create');
    }

    // Store new inventory item
    public function store(Request $request)
    {
        $data = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'item_type' => 'nullable|string|max:100',
            'reorder_level' => 'required|integer|min:0',
        ]);
        $data['location'] = 'retail';
        Inventory::create($data);
        return redirect()->route('retail.inventory.check')->with('status', 'Inventory item added successfully.');
    }

    // Show a single inventory item and its adjustment/order history
    public function show($id)
    {
        $inventory = \App\Models\Inventory::findOrFail($id);
        // Get all order items that affected this inventory
        $orderItems = \App\Models\OrderItem::with('order')
            ->where('product_id', $inventory->product_id)
            ->orderByDesc('created_at')
            ->get();
        return view('retail.inventory.show', compact('inventory', 'orderItems'));
    }

    public function edit($id)
    {
        $item = Inventory::findOrFail($id);
        return view('retail.inventory.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Inventory::findOrFail($id);
        $data = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'item_type' => 'nullable|string|max:100',
            'reorder_level' => 'required|integer|min:0',
        ]);
        $item->update($data);
        return redirect()->route('retail.inventory.index')->with('status', 'Inventory item updated successfully.');
    }

    public function destroy($id)
    {
        $item = Inventory::findOrFail($id);
        $item->delete();
        return redirect()->route('retail.inventory.index')->with('status', 'Inventory item deleted successfully.');
    }
}
