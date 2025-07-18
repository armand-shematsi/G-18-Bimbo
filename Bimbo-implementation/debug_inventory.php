<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inventory;
use App\Models\Product;

echo "=== INVENTORY DEBUG ===\n\n";

// Check all products and their inventory
$products = Product::all();
foreach ($products as $product) {
    echo "Product: {$product->name} (ID: {$product->id})\n";

    $bakeryInventory = Inventory::where('product_id', $product->id)
        ->where('location', 'bakery')
        ->first();

    $retailInventory = Inventory::where('product_id', $product->id)
        ->where('location', 'retail')
        ->first();

    echo "  Bakery: " . ($bakeryInventory ? $bakeryInventory->quantity : 'NOT FOUND') . "\n";
    echo "  Retail: " . ($retailInventory ? $retailInventory->quantity : 'NOT FOUND') . "\n";

    // If bakery inventory is missing, create it
    if (!$bakeryInventory) {
        $bakeryInventory = Inventory::create([
            'product_id' => $product->id,
            'item_name' => $product->name,
            'quantity' => 100,
            'unit_price' => $product->price ?? 0,
            'unit' => 'pieces',
            'item_type' => 'finished_good',
            'location' => 'bakery',
            'reorder_level' => 10,
        ]);
        echo "  ✅ Created bakery inventory\n";
    }

    // If retail inventory is missing, create it
    if (!$retailInventory) {
        $retailInventory = Inventory::create([
            'product_id' => $product->id,
            'item_name' => $product->name,
            'quantity' => 50,
            'unit_price' => $product->price ?? 0,
            'unit' => 'pieces',
            'item_type' => 'finished_good',
            'location' => 'retail',
            'reorder_level' => 5,
        ]);
        echo "  ✅ Created retail inventory\n";
    }

    echo "\n";
}

echo "=== FINAL COUNT ===\n";
$bakeryCount = Inventory::where('location', 'bakery')->count();
$retailCount = Inventory::where('location', 'retail')->count();
$productCount = Product::count();

echo "Products: $productCount\n";
echo "Bakery inventory: $bakeryCount\n";
echo "Retail inventory: $retailCount\n";

if ($bakeryCount == $productCount && $retailCount == $productCount) {
    echo "✅ All products have both bakery and retail inventory!\n";
} else {
    echo "❌ Inventory mismatch detected!\n";
}
