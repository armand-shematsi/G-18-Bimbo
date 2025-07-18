<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Order;
use App\Models\RetailerOrder;

echo "=== INVENTORY LOCATION FIX SCRIPT ===\n\n";

// 1. First, let's see what we have
echo "1. Current inventory locations:\n";
$locations = Inventory::select('location')->distinct()->get()->pluck('location');
foreach ($locations as $location) {
    $count = Inventory::where('location', $location)->count();
    echo "- '$location' ($count items)\n";
}

echo "\n2. Current products:\n";
$products = Product::all();
foreach ($products as $product) {
    echo "- ID: {$product->id}, Name: {$product->name}\n";
}

echo "\n3. Fixing inventory locations...\n";

// 2. Update all 'warehouse A' to 'bakery'
$warehouseItems = Inventory::where('location', 'warehouse A')->get();
echo "Updating " . $warehouseItems->count() . " items from 'warehouse A' to 'bakery'\n";
foreach ($warehouseItems as $item) {
    $item->location = 'bakery';
    $item->save();
    echo "Updated: {$item->item_name} -> bakery\n";
}

// 3. Ensure all products have inventory records in both bakery and retail
echo "\n4. Creating missing inventory records...\n";
foreach ($products as $product) {
    // Check bakery inventory
    $bakeryInventory = Inventory::where('product_id', $product->id)
        ->where('location', 'bakery')
        ->first();

    if (!$bakeryInventory) {
        $bakeryInventory = Inventory::create([
            'product_id' => $product->id,
            'item_name' => $product->name,
            'quantity' => 100, // Default starting quantity
            'unit_price' => $product->price ?? 0,
            'unit' => 'pieces',
            'item_type' => 'finished_good',
            'location' => 'bakery',
            'reorder_level' => 10,
        ]);
        echo "Created bakery inventory for: {$product->name}\n";
    }

    // Check retail inventory
    $retailInventory = Inventory::where('product_id', $product->id)
        ->where('location', 'retail')
        ->first();

    if (!$retailInventory) {
        $retailInventory = Inventory::create([
            'product_id' => $product->id,
            'item_name' => $product->name,
            'quantity' => 50, // Default starting quantity
            'unit_price' => $product->price ?? 0,
            'unit' => 'pieces',
            'item_type' => 'finished_good',
            'location' => 'retail',
            'reorder_level' => 5,
        ]);
        echo "Created retail inventory for: {$product->name}\n";
    }
}

// 4. Fix RetailerOrderController to use consistent location
echo "\n5. Updating RetailerOrderController to use 'bakery' instead of 'Bakery Storage'...\n";

$controllerFile = 'app/Http/Controllers/Retail/RetailerOrderController.php';
$controllerContent = file_get_contents($controllerFile);

// Replace 'Bakery Storage' with 'bakery' in the fulfill method
$controllerContent = str_replace(
    "->where('location', 'Bakery Storage')",
    "->where('location', 'bakery')",
    $controllerContent
);

file_put_contents($controllerFile, $controllerContent);
echo "Updated RetailerOrderController\n";

// 5. Verify the fix
echo "\n6. Final inventory status:\n";
$finalLocations = Inventory::select('location')->distinct()->get()->pluck('location');
foreach ($finalLocations as $location) {
    $count = Inventory::where('location', $location)->count();
    echo "- '$location' ($count items)\n";
}

echo "\n7. Testing inventory adjustment logic...\n";

// Test with a recent order
$recentOrder = Order::with('items')->latest()->first();
if ($recentOrder) {
    echo "Testing with Order #{$recentOrder->id}\n";
    foreach ($recentOrder->items as $item) {
        $bakeryInventory = Inventory::where('product_id', $item->product_id)
            ->where('location', 'bakery')
            ->first();
        $retailInventory = Inventory::where('product_id', $item->product_id)
            ->where('location', 'retail')
            ->first();

        echo "Item: {$item->product_name} (Qty: {$item->quantity})\n";
        echo "  Bakery inventory: " . ($bakeryInventory ? $bakeryInventory->quantity : 'NOT FOUND') . "\n";
        echo "  Retail inventory: " . ($retailInventory ? $retailInventory->quantity : 'NOT FOUND') . "\n";
    }
}

echo "\n=== FIX COMPLETE ===\n";
echo "Inventory locations have been standardized to:\n";
echo "- 'bakery' for bakery inventory\n";
echo "- 'retail' for retail inventory\n";
echo "- All products now have inventory records in both locations\n";
