<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockIn;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StockInController extends Controller
{
    // List all stock-in events for the logged-in supplier
    public function index()
    {
        $supplierId = Auth::id();
        $stockIns = StockIn::where('supplier_id', $supplierId)
            ->with('inventory')
            ->orderBy('received_at', 'desc')
            ->get();
        return view('supplier.stockin.index', compact('stockIns'));
    }

    // Show form to add new stock-in for an inventory item
    public function create()
    {
        $supplierId = Auth::id();
        $inventory = Inventory::where('user_id', $supplierId)->get();
        return view('supplier.stockin.create', compact('inventory'));
    }

    // Store the stock-in event, update inventory quantity
    public function store(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'quantity_received' => 'required|integer|min:1',
            'received_at' => 'required|date',
        ]);

        // Fetch the vendor record for the logged-in user
        $vendor = \App\Models\Vendor::where('user_id', Auth::id())->first();
        if (!$vendor) {
            return back()->withErrors(['You are not registered as a vendor.']);
        }
        $supplierId = $vendor->id;

        $inventory = Inventory::where('user_id', Auth::id())->findOrFail($request->inventory_id);

        // Create stock-in record
        $stockIn = StockIn::create([
            'supplier_id' => $supplierId,
            'inventory_id' => $inventory->id,
            'quantity_received' => $request->quantity_received,
            'received_at' => $request->received_at,
        ]);
        Log::info('StockIn created:', $stockIn ? $stockIn->toArray() : ['failed' => true]);

        if (!$stockIn) {
            return back()->withErrors(['Failed to add stock record. Please try again.']);
        }

        // Update inventory quantity
        $inventory->quantity += $request->quantity_received;
        $inventory->save();

        return redirect()->route('supplier.stockin.index')->with('success', 'Stock added successfully.');
    }
}
