<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    /**
     * Display the supplier's inventory.
     */
    public function index()
    {
        $inventory = Inventory::where('user_id', auth()->id())->get();
        return view('supplier.inventory.index', compact('inventory'));
    }

    /**
     * Show the form for creating a new inventory item.
     */
    public function create()
    {
        return view('supplier.inventory.create');
    }

    /**
     * Store a newly created inventory item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_type' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'status' => 'required|in:available,low_stock,out_of_stock',
            'reorder_level' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $validated['user_id'] = auth()->id();

        Inventory::create($validated);

        return redirect()->route('supplier.inventory.index')
            ->with('success', 'Inventory item added successfully.');
    }

    /**
     * Show the form for editing the specified inventory item.
     */
    public function edit($id)
    {
        $item = Inventory::where('user_id', auth()->id())->findOrFail($id);
        return view('supplier.inventory.edit', compact('item'));
    }

    /**
     * Update the specified inventory item in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_type' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'status' => 'required|in:available,low_stock,out_of_stock',
            'reorder_level' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $validated['user_id'] = auth()->id();

        $item = Inventory::where('user_id', auth()->id())->findOrFail($id);
        $item->update($validated);

        return redirect()->route('supplier.inventory.index')
            ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified inventory item from storage.
     */
    public function destroy($id)
    {
        $item = Inventory::where('user_id', auth()->id())->findOrFail($id);
        $item->delete();

        return redirect()->route('supplier.inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    /**
     * Update the quantity of an inventory item.
     */
    public function updateQuantity(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $item = Inventory::where('user_id', auth()->id())->findOrFail($id);
        $item->update($validated);

        return redirect()->route('supplier.inventory.index')
            ->with('success', 'Inventory quantity updated successfully.');
    }

    /**
     * Show the inventory summary dashboard.
     */
    public function dashboard()
    {
        $userId = auth()->id();
        $inventory = \App\Models\Inventory::where('user_id', $userId)->get();
        $stats = [
            'total' => $inventory->count(),
            'available' => $inventory->where('status', 'available')->count(),
            'low_stock' => $inventory->where('status', 'low_stock')->count(),
            'out_of_stock' => $inventory->where('status', 'out_of_stock')->count(),
            'over_stock' => $inventory->where('status', 'available')->count(), // Over stock = available
        ];

        // Total Stock In
        $totalStockIn = \App\Models\StockIn::where('user_id', $userId)->sum('quantity_received');
        // Total Stock Out
        $totalStockOut = \App\Models\StockOut::where('user_id', $userId)->sum('quantity_removed');

        // Daily Movement (last 7 days)
        $stockInDaily = \App\Models\StockIn::where('user_id', $userId)
            ->where('received_at', '>=', now()->subDays(7))
            ->get()
            ->groupBy(function($item) { return \Carbon\Carbon::parse($item->received_at)->format('Y-m-d'); });
        $stockOutDaily = \App\Models\StockOut::where('user_id', $userId)
            ->where('removed_at', '>=', now()->subDays(7))
            ->get()
            ->groupBy(function($item) { return \Carbon\Carbon::parse($item->removed_at)->format('Y-m-d'); });
        $dates = collect(range(0, 6))->map(function($i) { return now()->subDays(6 - $i)->format('Y-m-d'); });
        $stockInData = $dates->map(fn($date) => isset($stockInDaily[$date]) ? $stockInDaily[$date]->sum('quantity_received') : 0);
        $stockOutData = $dates->map(fn($date) => isset($stockOutDaily[$date]) ? $stockOutDaily[$date]->sum('quantity_removed') : 0);

        // Top Selling Items (by quantity)
        $topSelling = \App\Models\OrderItem::select('product_name')
            ->selectRaw('SUM(quantity) as total_sold')
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Prepare for view
        return view('supplier.inventory.dashboard', [
            'stats' => $stats,
            'totalStockIn' => $totalStockIn,
            'totalStockOut' => $totalStockOut,
            'stockInData' => $stockInData,
            'stockOutData' => $stockOutData,
            'dates' => $dates,
            'inventory' => $inventory,
            'topSelling' => $topSelling,
        ]);
    }
}
