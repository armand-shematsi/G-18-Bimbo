<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use App\Models\InventoryMovement;
use App\Notifications\LowStockNotification;

class InventoryController extends Controller
{
    /**
     * Display the supplier's inventory.
     */
    public function index()
    {
        $inventory = Inventory::where('user_id', auth()->id())
            ->with(['movements' => function($query) {
                $query->latest()->limit(5);
            }])
            ->orderBy('item_name')
            ->get();

        // Calculate summary statistics
        $totalItems = $inventory->count();
        $totalValue = $inventory->sum(function($item) {
            return $item->quantity * $item->unit_price;
        });
        $lowStockItems = $inventory->where('status', 'low_stock')->count();
        $outOfStockItems = $inventory->where('status', 'out_of_stock')->count();

        return view('supplier.inventory.index', compact('inventory', 'totalItems', 'totalValue', 'lowStockItems', 'outOfStockItems'));
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

        $item = Inventory::create($validated);

        // Log initial stock-in movement
        InventoryMovement::create([
            'inventory_id' => $item->id,
            'user_id' => auth()->id(),
            'type' => 'in',
            'quantity' => $item->quantity,
            'note' => 'Initial stock-in on creation',
        ]);

        return redirect()->route('supplier.inventory.index')
            ->with('success', 'Inventory item added successfully.');
    }

    /**
     * Show the form for editing the specified inventory item.
     */
    public function edit($id)
    {
        $item = Inventory::where('user_id', auth()->id())->findOrFail($id);
        $movements = $item->movements()->with('user')->orderByDesc('created_at')->get();
        return view('supplier.inventory.edit', compact('item', 'movements'));
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
        $oldQuantity = $item->quantity;
        $newQuantity = $validated['quantity'];
        $diff = $newQuantity - $oldQuantity;
        $type = $diff > 0 ? 'in' : ($diff < 0 ? 'out' : 'adjustment');

        $item->update($validated);

        if ($diff !== 0) {
            InventoryMovement::create([
                'inventory_id' => $item->id,
                'user_id' => auth()->id(),
                'type' => $type,
                'quantity' => abs($diff),
                'note' => 'Quantity updated by supplier',
            ]);
        }

        // Send low stock notification if now at or below reorder level
        if ($item->quantity <= $item->reorder_level) {
            // Notify supplier (current user)
            auth()->user()->notify(new LowStockNotification($item));
            // Notify admin (first admin user)
            $admin = \App\Models\User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new LowStockNotification($item));
            }
        }

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

        // Bar chart data: item names and quantities
        $barLabels = $inventory->pluck('item_name');
        $barData = $inventory->pluck('quantity');

        // Pie chart data: status counts
        $statusCounts = [
            'Available' => $inventory->where('status', 'available')->count(),
            'Low Stock' => $inventory->where('status', 'low_stock')->count(),
            'Out of Stock' => $inventory->where('status', 'out_of_stock')->count(),
        ];

        return view('supplier.inventory.dashboard', compact('barLabels', 'barData', 'statusCounts'));
    }

    /**
     * Display the specified inventory item with detailed stock movements.
     */
    public function show($id)
    {
        $item = Inventory::where('user_id', auth()->id())
            ->with(['movements' => function($query) {
                $query->orderByDesc('created_at');
            }])
            ->findOrFail($id);

        // Get stock in/out history
        $stockInHistory = \App\Models\StockIn::where('user_id', auth()->id())
            ->where('inventory_id', $item->id)
            ->orderByDesc('received_at')
            ->get();

        $stockOutHistory = \App\Models\StockOut::where('user_id', auth()->id())
            ->where('inventory_id', $item->id)
            ->orderByDesc('removed_at')
            ->get();

        // Calculate statistics
        $totalStockIn = $stockInHistory->sum('quantity_received');
        $totalStockOut = $stockOutHistory->sum('quantity_removed');
        $currentStock = $item->quantity;
        $totalValue = $currentStock * $item->unit_price;

        // Get recent movements (last 10)
        $recentMovements = $item->movements()->with('user')->orderByDesc('created_at')->limit(10)->get();

        return view('supplier.inventory.show', compact(
            'item',
            'stockInHistory',
            'stockOutHistory',
            'totalStockIn',
            'totalStockOut',
            'currentStock',
            'totalValue',
            'recentMovements'
        ));
    }
}
