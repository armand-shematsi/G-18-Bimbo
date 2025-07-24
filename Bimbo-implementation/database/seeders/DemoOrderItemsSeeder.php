<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoOrderItemsSeeder extends Seeder
{
    public function run()
    {
        $orderId = \App\Models\Order::first()->id ?? 1; // Use first order or fallback to 1
        $products = [
            'white bread' => 2.50,
            'baguette' => 2.80,
            'multigrain bread' => 3.50,
        ];
        for ($i = 1; $i <= 20; $i++) {
            $quantity1 = rand(8, 20);
            \App\Models\OrderItem::create([
                'order_id' => $orderId,
                'product_name' => 'white bread',
                'quantity' => $quantity1,
                'unit_price' => $products['white bread'],
                'total_price' => $products['white bread'] * $quantity1,
                'created_at' => now()->subDays(21 - $i)
            ]);
            $quantity2 = rand(5, 15);
            \App\Models\OrderItem::create([
                'order_id' => $orderId,
                'product_name' => 'baguette',
                'quantity' => $quantity2,
                'unit_price' => $products['baguette'],
                'total_price' => $products['baguette'] * $quantity2,
                'created_at' => now()->subDays(21 - $i)
            ]);
            $quantity3 = rand(10, 25);
            \App\Models\OrderItem::create([
                'order_id' => $orderId,
                'product_name' => 'multigrain bread',
                'quantity' => $quantity3,
                'unit_price' => $products['multigrain bread'],
                'total_price' => $products['multigrain bread'] * $quantity3,
                'created_at' => now()->subDays(21 - $i)
            ]);
        }
    }
}
