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
            [ 'item_name' => 'White Bread', 'item_type' => 'bread', 'quantity' => 100, 'unit' => 'pcs', 'reorder_level' => 20, 'location' => 'retail' ],
            [ 'item_name' => 'Whole Wheat Bread', 'item_type' => 'bread', 'quantity' => 80, 'unit' => 'pcs', 'reorder_level' => 15, 'location' => 'retail' ],
            [ 'item_name' => 'Multigrain Bread', 'item_type' => 'bread', 'quantity' => 60, 'unit' => 'pcs', 'reorder_level' => 10, 'location' => 'retail' ],
            [ 'item_name' => 'Sourdough Bread', 'item_type' => 'bread', 'quantity' => 50, 'unit' => 'pcs', 'reorder_level' => 10, 'location' => 'retail' ],
            [ 'item_name' => 'Rye Bread', 'item_type' => 'bread', 'quantity' => 40, 'unit' => 'pcs', 'reorder_level' => 8, 'location' => 'retail' ],
            [ 'item_name' => 'Baguette', 'item_type' => 'bread', 'quantity' => 30, 'unit' => 'pcs', 'reorder_level' => 8, 'location' => 'retail' ],
            [ 'item_name' => 'Brioche', 'item_type' => 'bread', 'quantity' => 25, 'unit' => 'pcs', 'reorder_level' => 5, 'location' => 'retail' ],
            [ 'item_name' => 'Ciabatta', 'item_type' => 'bread', 'quantity' => 20, 'unit' => 'pcs', 'reorder_level' => 5, 'location' => 'retail' ],
            [ 'item_name' => 'Focaccia', 'item_type' => 'bread', 'quantity' => 15, 'unit' => 'pcs', 'reorder_level' => 3, 'location' => 'retail' ],
            [ 'item_name' => 'Potato Bread', 'item_type' => 'bread', 'quantity' => 10, 'unit' => 'pcs', 'reorder_level' => 2, 'location' => 'retail' ],
            [ 'item_name' => 'Honey Oat Bread', 'item_type' => 'bread', 'quantity' => 10, 'unit' => 'pcs', 'reorder_level' => 2, 'location' => 'retail' ],
            [ 'item_name' => 'Milk Bread', 'item_type' => 'bread', 'quantity' => 10, 'unit' => 'pcs', 'reorder_level' => 2, 'location' => 'retail' ],
            [ 'item_name' => 'Challah', 'item_type' => 'bread', 'quantity' => 10, 'unit' => 'pcs', 'reorder_level' => 2, 'location' => 'retail' ],
            [ 'item_name' => 'Pita Bread', 'item_type' => 'bread', 'quantity' => 10, 'unit' => 'pcs', 'reorder_level' => 2, 'location' => 'retail' ],
            [ 'item_name' => 'Bagel', 'item_type' => 'bread', 'quantity' => 10, 'unit' => 'pcs', 'reorder_level' => 2, 'location' => 'retail' ],
            [ 'item_name' => 'English Muffin', 'item_type' => 'bread', 'quantity' => 10, 'unit' => 'pcs', 'reorder_level' => 2, 'location' => 'retail' ],
            [ 'item_name' => 'Dinner Roll', 'item_type' => 'bread', 'quantity' => 10, 'unit' => 'pcs', 'reorder_level' => 2, 'location' => 'retail' ],
            [ 'item_name' => 'Cinnamon Swirl Bread', 'item_type' => 'bread', 'quantity' => 10, 'unit' => 'pcs', 'reorder_level' => 2, 'location' => 'retail' ],
            [ 'item_name' => 'Gluten-Free Bread', 'item_type' => 'bread', 'quantity' => 10, 'unit' => 'pcs', 'reorder_level' => 2, 'location' => 'retail' ],
            [ 'item_name' => 'Rustic Country Loaf', 'item_type' => 'bread', 'quantity' => 10, 'unit' => 'pcs', 'reorder_level' => 2, 'location' => 'retail' ],
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
