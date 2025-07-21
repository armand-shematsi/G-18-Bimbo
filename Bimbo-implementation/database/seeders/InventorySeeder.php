<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\Ingredient;
use App\Models\User;

class InventorySeeder extends Seeder
{
    public function run()
    {
        $suppliers = \App\Models\User::where('role', 'supplier')->get();
        $ingredients = Ingredient::all();
        foreach ($suppliers as $supplier) {
            foreach ($ingredients as $ingredient) {
                $product = \App\Models\Product::where('name', $ingredient->name)->first();
                \App\Models\Inventory::updateOrCreate(
                    [
                        'item_name' => $ingredient->name,
                        'user_id' => $supplier->id,
                        'item_type' => 'raw_material',
                    ],
                    [
                        'item_name' => $ingredient->name,
                        'quantity' => $ingredient->stock_quantity ?? 1000,
                        'unit_price' => $product ? ($product->unit_price ?? 100) : 100,
                        'unit' => $ingredient->unit ?? 'unit',
                        'item_type' => 'raw_material',
                        'reorder_level' => $ingredient->low_stock_threshold ?? 10,
                        'location' => 'supplier',
                        'user_id' => $supplier->id,
                        'product_id' => $product ? $product->id : null,
                        'status' => 'available',
                    ]
                );
            }
        }
        // After seeding, ensure all inventory records have correct product_id
        $allInventories = \App\Models\Inventory::all();
        foreach ($allInventories as $inventory) {
            $product = \App\Models\Product::where('name', $inventory->item_name)->first();
            if ($product && $inventory->product_id !== $product->id) {
                $inventory->product_id = $product->id;
                $inventory->save();
            }
        }
        // Final fix: ensure every inventory record has the correct product_id
        $allInventories = \App\Models\Inventory::all();
        foreach ($allInventories as $inventory) {
            $product = \App\Models\Product::firstOrCreate(
                ['name' => $inventory->item_name],
                [
                    'unit_price' => $inventory->unit_price ?? 100,
                    'image_url' => null,
                ]
            );
            if ($inventory->product_id !== $product->id) {
                $inventory->product_id = $product->id;
                $inventory->save();
            }
        }
    }
}
