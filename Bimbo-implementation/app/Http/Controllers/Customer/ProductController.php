<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Inventory;

class ProductController extends Controller
{
    public function index()
    {
        // Get all products with available quantity and unit
        $products = Product::all()->map(function($product) {
            $inventory = Inventory::where('item_name', $product->name)->first();
            $product->available = $inventory ? $inventory->quantity : 0;
            $product->unit = $inventory ? $inventory->unit : '';
            return $product;
        });
        return view('customer.products', compact('products'));
    }
}
