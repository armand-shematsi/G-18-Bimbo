<?php

namespace App\Http\Controllers\Bakery;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventory items.
     */
    public function index(Request $request)
    {
        $query = Inventory::query();

        // Filter for bakery-relevant items only
        $query->whereIn('item_type', ['ingredient', 'finished_good', 'packaging']);

        // Filter by item type if specified
        if ($request->has('type') && $request->type !== '') {
            $query->where('item_type', $request->type);
        }

        // Filter by status if specified
        if ($request->has('status') && $request->status !== '') {
            switch ($request->status) {
                case 'low_stock':
                    $query->whereColumn('quantity', '<=', 'reorder_level');
                    break;
                case 'out_of_stock':
                    $query->where('quantity', 0);
                    break;
                case 'available':
                    $query->whereColumn('quantity', '>', 'reorder_level');
                    break;
            }
        }

        // Search by item name
        if ($request->has('search') && $request->search !== '') {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }

        $inventory = $query->orderBy('item_name')->paginate(15);

        // Get summary statistics for bakery items only
        $totalItems = Inventory::whereIn('item_type', ['ingredient', 'finished_good', 'packaging'])->count();
        $lowStockItems = Inventory::whereIn('item_type', ['ingredient', 'finished_good', 'packaging'])
            ->whereColumn('quantity', '<=', 'reorder_level')->count();
        $outOfStockItems = Inventory::whereIn('item_type', ['ingredient', 'finished_good', 'packaging'])
            ->where('quantity', 0)->count();
        $totalValue = Inventory::whereIn('item_type', ['ingredient', 'finished_good', 'packaging'])
            ->whereNotNull('unit_price')
            ->sum(DB::raw('quantity * unit_price'));

        // Get unique item types for filter (bakery items only)
        $itemTypes = Inventory::whereIn('item_type', ['ingredient', 'finished_good', 'packaging'])
            ->distinct()->pluck('item_type')->filter();

        return view('bakery.inventory.index', compact(
            'inventory',
            'totalItems',
            'lowStockItems',
            'outOfStockItems',
            'totalValue',
            'itemTypes'
        ));
    }

        /**
     * Show the form for creating a new inventory item.
     */
    public function create()
    {
        $products = Product::all();
        $itemTypes = ['ingredient', 'finished_good', 'packaging'];

        return view('bakery.inventory.create', compact('products', 'itemTypes'));
    }

    /**
     * Store a newly created inventory item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'unit' => 'required|string|max:50',
            'item_type' => 'required|string|max:100|in:ingredient,finished_good,packaging',
            'reorder_level' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $inventory = Inventory::create($validated);

        return redirect()->route('bakery.inventory.index')
            ->with('success', 'Inventory item created successfully.');
    }

    /**
     * Display the specified inventory item.
     */
    public function show(Inventory $inventory)
    {
        $movements = $inventory->movements()->orderBy('created_at', 'desc')->paginate(10);

        return view('bakery.inventory.show', compact('inventory', 'movements'));
    }

        /**
     * Show the form for editing the specified inventory item.
     */
    public function edit(Inventory $inventory)
    {
        $products = Product::all();
        $itemTypes = ['ingredient', 'finished_good', 'packaging'];

        return view('bakery.inventory.edit', compact('inventory', 'products', 'itemTypes'));
    }

    /**
     * Update the specified inventory item.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'unit' => 'required|string|max:50',
            'item_type' => 'required|string|max:100|in:ingredient,finished_good,packaging',
            'reorder_level' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $inventory->update($validated);

        return redirect()->route('bakery.inventory.index')
            ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified inventory item.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('bakery.inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    /**
     * Update stock quantity (for stock in/out operations).
     */
    public function updateStock(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity_change' => 'required|numeric',
            'movement_type' => 'required|in:in,out',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldQuantity = $inventory->quantity;

        if ($validated['movement_type'] === 'in') {
            $inventory->quantity += $validated['quantity_change'];
        } else {
            $inventory->quantity -= $validated['quantity_change'];
            if ($inventory->quantity < 0) {
                return back()->withErrors(['quantity_change' => 'Insufficient stock for this operation.']);
            }
        }

        $inventory->save();

        // Record the movement
        $inventory->movements()->create([
            'quantity_change' => $validated['movement_type'] === 'in' ? $validated['quantity_change'] : -$validated['quantity_change'],
            'movement_type' => $validated['movement_type'],
            'notes' => $validated['notes'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('bakery.inventory.show', $inventory)
            ->with('success', 'Stock updated successfully.');
    }

    /**
     * Get inventory data for AJAX requests.
     */
    public function apiIndex(Request $request)
    {
        $query = Inventory::query();

        // Filter for bakery-relevant items only
        $query->whereIn('item_type', ['ingredient', 'finished_good', 'packaging']);

        if ($request->has('type')) {
            $query->where('item_type', $request->type);
        }

        if ($request->has('status')) {
            switch ($request->status) {
                case 'low_stock':
                    $query->whereColumn('quantity', '<=', 'reorder_level');
                    break;
                case 'out_of_stock':
                    $query->where('quantity', 0);
                    break;
            }
        }

        $inventory = $query->orderBy('item_name')->get();

        return response()->json($inventory);
    }
}
