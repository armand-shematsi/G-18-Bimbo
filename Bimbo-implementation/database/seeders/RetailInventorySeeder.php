<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\User;

class RetailInventorySeeder extends Seeder
{
    public function run(): void
    {
        $retailManager = User::where('role', 'retail_manager')->first();
        if (!$retailManager) {
            $this->command->warn('No retail manager found. Seeder skipped.');
            return;
        }

        $items = [
            [
                'item_name' => 'Classic Bread Loaf',
                'item_type' => 'bread',
                'quantity' => 120,
                'unit' => 'pcs',
                'reorder_level' => 20,
            ],
            [
                'item_name' => 'Chocolate Cake',
                'item_type' => 'cakes',
                'quantity' => 15,
                'unit' => 'pcs',
                'reorder_level' => 5,
            ],
            [
                'item_name' => 'Croissant',
                'item_type' => 'pastries',
                'quantity' => 40,
                'unit' => 'pcs',
                'reorder_level' => 10,
            ],
            [
                'item_name' => 'Baguette',
                'item_type' => 'bread',
                'quantity' => 30,
                'unit' => 'pcs',
                'reorder_level' => 8,
            ],
        ];

        foreach ($items as $item) {
            Inventory::create(array_merge($item, [
                'user_id' => $retailManager->id,
            ]));
        }

        $vendor = \App\Models\Vendor::first();
        if ($vendor) {
            \App\Models\Order::create([
                'user_id' => $retailManager->id,
                'vendor_id' => $vendor->id,
                'customer_name' => 'Test Customer',
                'customer_email' => 'customer@example.com',
                'status' => \App\Models\Order::STATUS_DELIVERED,
                'total' => 15000,
                'delivered_at' => now(),
            ]);
        }
    }
}
