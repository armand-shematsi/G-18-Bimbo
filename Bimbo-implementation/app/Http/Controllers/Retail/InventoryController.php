<?php

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    public function index()
    {
        return view('retail.inventory');
    }

    public function supplierInventory()
    {
        $supplierInventory = \App\Models\Inventory::whereHas('user', function($query) {
            $query->where('role', 'supplier');
        })->with('user')->get();

        $lowStockItems = $supplierInventory->filter(function($item) {
            return $item->needsReorder();
        });

        return view('retail.supplier-inventory', compact('supplierInventory', 'lowStockItems'));
    }
}
