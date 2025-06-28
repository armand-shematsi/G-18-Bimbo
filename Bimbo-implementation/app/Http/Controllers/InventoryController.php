<?php

namespace App\Http\Controllers;
use App\models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            $inventory = \App\Models\Inventory::all();
        } elseif ($user->hasRole('supplier')) {
            $inventory = \App\Models\Inventory::where('user_id', $user->id)->get();
        } else {
            abort(403, 'Unauthorized');
        }
        return view('supplier.inventory.index', ['inventory' => $inventory]);
    }

    public function create()
    {
        return view('supplier.inventory.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
        ]);

        $validated['user_id'] = auth()->id();

        Inventory::create($validated);

        return redirect()->route('supplier.inventory.index')->with('success', 'Item added successfully!');
    }

    public function edit($id)
    {
        $item = Inventory::findOrFail($id);
        return view('supplier.inventory.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
        ]);

        $validated['user_id'] = auth()->id();

        $item = Inventory::findOrFail($id);
        $item->update($validated);

        return redirect()->route('supplier.inventory.index')->with('success', 'Item updated successfully!');
    }

    public function destroy($id)
    {
        $item = Inventory::findOrFail($id);
        $item->delete();

        return redirect()->route('supplier.inventory.index')->with('success', 'Item deleted successfully!');
    }
}
