<?php

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('type', 'finished_product')->get()->map(function($product) {
            $inventory = \App\Models\Inventory::where('item_name', $product->name)->first();
            $product->inventory_id = $inventory ? $inventory->id : null;
            return $product;
        });
        return view('retail.products.index', compact('products'));
    }
} 