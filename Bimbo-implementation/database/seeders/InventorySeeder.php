<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\User;

class InventorySeeder extends Seeder
{
    public function run()
    {
        $products = Product::whereIn('name', [
            'Brioche',
            'Milk Bread',
            'Pita Bread',
        ])->get();

        // Use the first supplier user for demo
        $supplier = User::where('role', 'supplier')->first();
        if (!$supplier) {
            $this->command->warn('No supplier found. Seeder skipped.');
            return;
        }

        foreach ($products as $product) {
            Inventory::firstOrCreate(
                ['item_name' => $product->name, 'user_id' => $supplier->id],
                [
                    'item_type' => 'bread',
                    'quantity' => 20,
                    'unit' => 'pcs',
                    'status' => 'available',
                    'reorder_level' => 5,
                    'unit_price' => $product->price ?? 100,
                ]
            );
        }
    }
} 