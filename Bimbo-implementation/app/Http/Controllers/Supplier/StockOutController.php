<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockOut;
use App\Models\Inventory;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;

class StockOutController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $stockOuts = StockOut::where('user_id', $userId)
            ->with('inventory')
            ->orderBy('removed_at', 'desc')
            ->get();
        return view('supplier.stockout.index', compact('stockOuts'));
    }

    public function create()
    {
        $userId = Auth::id();
        $inventory = Inventory::where('user_id', $userId)->get();
        return view('supplier.stockout.create', compact('inventory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'quantity_removed' => 'required|integer|min:1',
            'removed_at' => 'required|date',
        ]);

        $userId = Auth::id();
        $inventory = Inventory::where('user_id', $userId)->findOrFail($request->inventory_id);

        // Check if enough quantity exists
        if ($inventory->quantity < $request->quantity_removed) {
            return back()->withErrors(['Not enough inventory to remove the requested quantity.']);
        }

        // Create stock-out record
        StockOut::create([
            'user_id' => $userId,
            'inventory_id' => $inventory->id,
            'quantity_removed' => $request->quantity_removed,
            'removed_at' => $request->removed_at,
        ]);

        // Update inventory quantity
        $inventory->quantity -= $request->quantity_removed;
        $inventory->save();

        return redirect()->route('supplier.stockout.index')->with('success', 'Stock removed successfully.');
    }
}
