<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

class RetailInventoryController extends Controller
{
    // Show inventory and stock in/out form
    public function check()
    {
        // Only get items available in the retail shop
        $items = \App\Models\Inventory::where('location', 'retail')->get();
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
        \App\Models\Inventory::create($data);
        return redirect()->route('retail.inventory.check')->with('status', 'Inventory item added successfully.');
    }
}
