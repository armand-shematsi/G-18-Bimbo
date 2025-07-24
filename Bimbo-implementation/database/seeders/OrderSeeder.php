<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a retail manager user
        $retailManager = User::where('role', 'retail_manager')->first();
        if (!$retailManager) {
            $retailManager = User::create([
                'name' => 'Retail Manager',
                'email' => 'retail@example.com',
                'password' => bcrypt('password'),
                'role' => 'retail_manager',
            ]);
        }

        // Get the first available vendor
        $vendor = \App\Models\Vendor::first();
        if (!$vendor) {
            $this->command->error('No vendor found. Please run VendorSeeder first.');
            return;
        }

        // Get or create some products
        $products = [
            ['name' => 'White Bread'],
            ['name' => 'Whole Wheat Bread'],
            ['name' => 'Sourdough Bread'],
            ['name' => 'Baguette'],
            ['name' => 'Croissant'],
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(['name' => $productData['name']], $productData);
        }

        $products = Product::all();

        // Create sample orders for the last 7 days
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays($i);

            // Create 2-5 orders per day
            $ordersPerDay = rand(2, 5);

            for ($j = 0; $j < $ordersPerDay; $j++) {
                $order = Order::create([
                    'user_id' => $retailManager->id,
                    'vendor_id' => $vendor->id,
                    'customer_name' => 'Customer ' . ($i * $ordersPerDay + $j + 1),
                    'customer_email' => 'customer' . ($i * $ordersPerDay + $j + 1) . '@example.com',
                    'status' => ['pending', 'processing', 'shipped', 'delivered'][rand(0, 3)],
                    'total' => rand(1500, 5000) / 100, // Random amount between $15 and $50
                    'payment_status' => 'paid',
                    'placed_at' => $date->copy()->addHours(rand(9, 17)), // Random time during business hours
                    'created_at' => $date->copy()->addHours(rand(9, 17)),
                    'updated_at' => $date->copy()->addHours(rand(9, 17)),
                ]);

                // Add 1-3 items to each order
                $numItems = rand(1, 3);
                $selectedProducts = $products->random($numItems);

                foreach ($selectedProducts as $product) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => rand(1, 5),
                        'unit_price' => rand(200, 800) / 100, // Random price between $2 and $8
                        'total_price' => rand(200, 800) / 100 * rand(1, 5),
                    ]);
                }
            }
        }

        // Create some inventory for the retail manager
        $inventoryItems = [
            ['item_name' => 'White Bread', 'quantity' => 50, 'unit_price' => 3.50, 'reorder_level' => 10],
            ['item_name' => 'Whole Wheat Bread', 'quantity' => 30, 'unit_price' => 4.00, 'reorder_level' => 8],
            ['item_name' => 'Sourdough Bread', 'quantity' => 25, 'unit_price' => 4.50, 'reorder_level' => 5],
            ['item_name' => 'Baguette', 'quantity' => 40, 'unit_price' => 2.50, 'reorder_level' => 12],
            ['item_name' => 'Croissant', 'quantity' => 60, 'unit_price' => 2.00, 'reorder_level' => 15],
        ];

        foreach ($inventoryItems as $item) {
            \App\Models\Inventory::firstOrCreate(
                ['item_name' => $item['item_name'], 'user_id' => $retailManager->id],
                array_merge($item, [
                    'user_id' => $retailManager->id,
                    'item_type' => 'bread',
                    'unit' => 'pieces',
                    'status' => 'available',
                ])
            );
        }

        $this->command->info('Sample orders and inventory created successfully!');
    }
}
