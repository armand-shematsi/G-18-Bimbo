<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoOrderItemsSeeder extends Seeder
{
    public function run()
    {
        $orderId = \App\Models\Order::first()->id ?? 1; // Use first order or fallback to 1
        for ($i = 1; $i <= 20; $i++) {
            \App\Models\OrderItem::create([
                'order_id' => $orderId,
                'product_name' => 'white bread',
                'quantity' => rand(8, 20),
                'created_at' => now()->subDays(21 - $i)
            ]);
            \App\Models\OrderItem::create([
                'order_id' => $orderId,
                'product_name' => 'baguette',
                'quantity' => rand(5, 15),
                'created_at' => now()->subDays(21 - $i)
            ]);
            \App\Models\OrderItem::create([
                'order_id' => $orderId,
                'product_name' => 'multigrain bread',
                'quantity' => rand(10, 25),
                'created_at' => now()->subDays(21 - $i)
            ]);
        }
    }
}
