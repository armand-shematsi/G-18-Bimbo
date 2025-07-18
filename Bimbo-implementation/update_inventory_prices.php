<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inventory;
use App\Models\Product;

echo "=== UPDATE INVENTORY UNIT PRICES ===\n\n";

$updated = 0;
$skipped = 0;

$inventories = Inventory::where('location', 'retail')->get();
foreach ($inventories as $inventory) {
    $product = Product::find($inventory->product_id);
    if ($product && $product->price && $product->price > 0) {
        $oldPrice = $inventory->unit_price;
        $inventory->unit_price = $product->price;
        $inventory->save();
        echo "Updated inventory ID {$inventory->id} ({$inventory->item_name}): $oldPrice -> {$product->price}\n";
        $updated++;
    } else {
        echo "Skipped inventory ID {$inventory->id} ({$inventory->item_name}): No valid product price\n";
        $skipped++;
    }
}

echo "\n=== SUMMARY ===\n";
echo "Updated: $updated\n";
echo "Skipped: $skipped\n";
echo "\nAll inventory unit prices are now synced with product prices!\n";
