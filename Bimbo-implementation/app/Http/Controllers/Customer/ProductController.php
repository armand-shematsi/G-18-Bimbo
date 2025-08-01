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
        // Only get finished products
        $products = Product::where('type', 'finished_product')->get()->map(function($product) {
            $inventory = Inventory::where('item_name', $product->name)
                ->where('location', 'retail')
                ->first();
            $product->available = $inventory ? $inventory->quantity : 0;
            $product->unit = $inventory ? $inventory->unit : '';
            $product->inventory_id = $inventory ? $inventory->id : null;
            $product->unit_price = $inventory ? $inventory->unit_price : ($product->unit_price ?? $product->price ?? 0);
            return $product;
        });
        return view('customer.products', compact('products'));
    }
}
