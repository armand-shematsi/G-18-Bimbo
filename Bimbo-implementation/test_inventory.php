<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;

echo "=== Current Retail Inventory ===\n";
$retailInventory = Inventory::where('location', 'retail')->get();
foreach ($retailInventory as $item) {
    echo "ID: {$item->id}, Name: {$item->item_name}, Quantity: {$item->quantity}, Location: {$item->location}\n";
}

echo "\n=== Recent Orders ===\n";
$recentOrders = Order::with('items')->latest()->take(5)->get();
foreach ($recentOrders as $order) {
    echo "Order #{$order->id}: Status: {$order->status}, Items: " . $order->items->count() . "\n";
    foreach ($order->items as $item) {
        echo "  - {$item->product_name}: {$item->quantity}\n";
    }
}

echo "\n=== Testing Inventory Update Logic ===\n";
// Test the inventory update logic with delivered orders
$testOrder = Order::with('items')->where('status', 'delivered')->first();
if ($testOrder) {
    echo "Testing with Order #{$testOrder->id} (status: {$testOrder->status})\n";
    foreach ($testOrder->items as $item) {
        $inventory = Inventory::where('item_name', $item->product_name)
            ->where('location', 'retail')
            ->first();

        if ($inventory) {
            echo "Found inventory for {$item->product_name}: {$inventory->quantity}\n";
            echo "Would deduct: {$item->quantity}\n";
            echo "New quantity would be: " . ($inventory->quantity - $item->quantity) . "\n";
        } else {
            echo "No inventory found for {$item->product_name}\n";
        }
    }
} else {
    echo "No delivered orders found\n";
}

echo "\n=== Testing Manual Inventory Update ===\n";
// Test manually updating inventory for delivered orders
$deliveredOrders = Order::with('items')->where('status', 'delivered')->get();
foreach ($deliveredOrders as $order) {
    echo "Processing Order #{$order->id}\n";
    foreach ($order->items as $item) {
        $inventory = Inventory::where('item_name', $item->product_name)
            ->where('location', 'retail')
            ->first();

        if ($inventory) {
            $oldQuantity = $inventory->quantity;
            $inventory->quantity -= $item->quantity;
            $inventory->save();
            echo "Updated {$item->product_name}: {$oldQuantity} -> {$inventory->quantity}\n";
        }
    }
}
