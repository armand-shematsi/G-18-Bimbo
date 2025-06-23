<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display the supplier's inventory.
     */
    public function index()
    {
        return view('supplier.inventory.index');
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
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'status' => 'required|in:available,low_stock,out_of_stock',
        ]);

        // TODO: Store the inventory item in the database

        return redirect()->route('supplier.inventory')
            ->with('success', 'Inventory item added successfully.');
    }
} 