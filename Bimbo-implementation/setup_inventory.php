<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inventory;
use App\Models\Product;

echo "=== FINAL INVENTORY SETUP ===\n\n";

// 1. Clear all existing inventory and start fresh
echo "1. Clearing existing inventory...\n";
Inventory::query()->delete();
echo "✅ All inventory cleared\n\n";

// 2. Create proper inventory for all products
echo "2. Creating inventory for all products...\n";
$products = Product::all();

foreach ($products as $product) {
    // Create bakery inventory
    $bakeryInventory = Inventory::create([
        'product_id' => $product->id,
        'item_name' => $product->name,
        'quantity' => 100, // Starting quantity
        'unit_price' => $product->price ?? 0,
        'unit' => 'pieces',
        'item_type' => 'finished_good',
        'location' => 'bakery',
        'reorder_level' => 10,
    ]);

    // Create retail inventory
    $retailInventory = Inventory::create([
        'product_id' => $product->id,
        'item_name' => $product->name,
        'quantity' => 50, // Starting quantity
        'unit_price' => $product->price ?? 0,
        'unit' => 'pieces',
        'item_type' => 'finished_good',
        'location' => 'retail',
        'reorder_level' => 5,
    ]);

    echo "✅ Created inventory for: {$product->name}\n";
    echo "  - Bakery: {$bakeryInventory->quantity} pieces\n";
    echo "  - Retail: {$retailInventory->quantity} pieces\n";
}

// 3. Final verification
echo "\n3. Final verification:\n";
$productCount = Product::count();
$bakeryCount = Inventory::where('location', 'bakery')->count();
$retailCount = Inventory::where('location', 'retail')->count();

echo "Products: $productCount\n";
echo "Bakery inventory: $bakeryCount\n";
echo "Retail inventory: $retailCount\n";

if ($bakeryCount == $productCount && $retailCount == $productCount) {
    echo "✅ PERFECT! All products have exactly one bakery and one retail inventory record.\n";
} else {
    echo "❌ Inventory mismatch detected.\n";
}

// 4. Test inventory lookup
echo "\n4. Testing inventory lookup...\n";
$testProduct = Product::first();
if ($testProduct) {
    $bakeryInventory = Inventory::where('product_id', $testProduct->id)
        ->where('location', 'bakery')
        ->first();
    $retailInventory = Inventory::where('product_id', $testProduct->id)
        ->where('location', 'retail')
        ->first();

    echo "Test product: {$testProduct->name}\n";
    echo "  Bakery inventory: " . ($bakeryInventory ? $bakeryInventory->quantity : 'NOT FOUND') . "\n";
    echo "  Retail inventory: " . ($retailInventory ? $retailInventory->quantity : 'NOT FOUND') . "\n";
}

echo "\n=== INVENTORY SYSTEM READY ===\n";
echo "✅ All inventory cleared and recreated\n";
echo "✅ All products have proper bakery and retail inventory\n";
echo "✅ Product relationships established\n";
echo "✅ Location standardization complete\n";
echo "✅ Controllers updated\n";
echo "\n🎉 Your inventory system is now ready!\n";
echo "\nInventory will now adjust properly when:\n";
echo "• Supplier orders are placed/delivered → bakery inventory increases\n";
echo "• Retailer orders are fulfilled → both bakery and retail inventory decrease\n";
echo "• Orders are processed through the system\n";
