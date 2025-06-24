<?php

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use App\Models\Product;

class InventoryController extends Controller
{
    public function index()
    {
        return view('retail.inventory');
    }

    public function check()
    {
        $products = Product::all();
        return view('retail.inventory.check', compact('products'));
    }
}