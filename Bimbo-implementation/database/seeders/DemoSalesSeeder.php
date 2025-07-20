<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Product;

class DemoSalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $statuses = ['pending', 'delivered', 'cancelled'];
        $payment_statuses = ['pending', 'paid', 'failed'];
        $fulfillment_types = ['pickup', 'delivery'];
        $delivery_options = ['standard', 'express', 'overnight'];

        // Get all product IDs and details
        $products = Product::all();
        $productCount = $products->count();
        if ($productCount === 0) return; // No products to use

        for ($i = 0; $i < 1000; $i++) {
            $orderId = DB::table('orders')->insertGetId([
                'total_amount'      => 0, // Will update after adding items
                'user_id'           => rand(1, 10),
                'vendor_id'         => rand(1, 5),
                'customer_name'     => 'Customer ' . Str::random(5),
                'customer_email'    => Str::random(5) . '@example.com',
                'status'            => $statuses[array_rand($statuses)],
                'payment_status'    => $payment_statuses[array_rand($payment_statuses)],
                'shipping_address'  => '123 Main St, City ' . rand(1, 100),
                'billing_address'   => '456 Side St, City ' . rand(1, 100),
                'placed_at'         => $now->copy()->subDays(rand(0, 365)),
                'fulfillment_type'  => $fulfillment_types[array_rand($fulfillment_types)],
                'tracking_number'   => strtoupper(Str::random(10)),
                'delivery_option'   => $delivery_options[array_rand($delivery_options)],
                'total'             => 0, // Will update after adding items
                'created_at'        => $now->copy()->subDays(rand(0, 365)),
                'updated_at'        => $now->copy()->subDays(rand(0, 365)),
                'notes'             => rand(0, 1) ? 'Test order note' : null,
                'delivered_at'      => rand(0, 1) ? $now->copy()->subDays(rand(0, 365)) : null,
            ]);

            $orderTotal = 0;
            $itemCount = rand(1, 5);
            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $quantity = rand(1, 10);
                $unitPrice = $product->price ?? $product->unit_price ?? rand(100, 10000) / 100;
                $totalPrice = $unitPrice * $quantity;
                $orderTotal += $totalPrice;

                DB::table('order_items')->insert([
                    'order_id'     => $orderId,
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'quantity'     => $quantity,
                    'unit_price'   => $unitPrice,
                    'total_price'  => $totalPrice,
                    'created_at'   => $now->copy()->subDays(rand(0, 365)),
                    'updated_at'   => $now->copy()->subDays(rand(0, 365)),
                ]);
            }
            // Update order total
            DB::table('orders')->where('id', $orderId)->update([
                'total_amount' => $orderTotal,
                'total'        => $orderTotal,
            ]);
        }
    }
}
