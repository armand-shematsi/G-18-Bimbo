<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fetch all orders
        $orders = DB::table('orders')->get();

        foreach ($orders as $order) {
            // Get the first order item for product_id
            $orderItem = DB::table('order_items')->where('order_id', $order->id)->first();
            $product_id = $orderItem->product_id ?? null;
            $quantity = DB::table('order_items')->where('order_id', $order->id)->sum('quantity') ?? 1;

            if ($product_id) {
                DB::table('retailer_orders')->insert([
                    'retailer_id' => $order->user_id,
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally, remove migrated orders (if needed)
        // DB::table('retailer_orders')->truncate();
    }
}; 