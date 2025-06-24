<?php

namespace App\Http\Controllers;
use App\models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = Inventory::all();
        return view('supplier.inventory.index',['inventory'=>$inventory]);
    }

    public function create()
    {
        return view('supplier.inventory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'item_type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
        ]);

        Inventory::create($request->only('item_name', 'item_type', 'quantity', 'unit'));

        return redirect()->route('supplier.inventory.index')->with('success', 'Item added successfully!');
    }

    public function edit($id)
    {
        $item = Inventory::findOrFail($id);
        return view('supplier.inventory.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'item_type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
        ]);

        $item = Inventory::findOrFail($id);
        $item->update($request->only('item_name', 'item_type', 'quantity', 'unit'));

        return redirect()->route('supplier.inventory.index')->with('success', 'Item updated successfully!');
    }

    public function destroy($id)
    {
        $item = Inventory::findOrFail($id);
        $item->delete();

        return redirect()->route('supplier.inventory.index')->with('success', 'Item deleted successfully!');
    }
}
