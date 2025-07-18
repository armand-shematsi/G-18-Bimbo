<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inventory;

echo "=== INVENTORY LOCATION ANALYSIS ===\n\n";

echo "1. All unique locations in database:\n";
$locations = Inventory::select('location')->distinct()->get()->pluck('location');
foreach ($locations as $location) {
    $count = Inventory::where('location', $location)->count();
    echo "- '$location' ($count items)\n";
}

echo "\n2. Items with NULL or empty location:\n";
$nullLocationItems = Inventory::whereNull('location')->orWhere('location', '')->get(['id', 'item_name', 'location']);
foreach ($nullLocationItems as $item) {
    echo "ID: {$item->id}, Name: {$item->item_name}, Location: " . ($item->location ?: 'NULL/EMPTY') . "\n";
}

echo "\n3. Items that should be bakery inventory (warehouse A):\n";
$warehouseItems = Inventory::where('location', 'warehouse A')->get(['id', 'item_name', 'location', 'item_type']);
foreach ($warehouseItems as $item) {
    echo "ID: {$item->id}, Name: {$item->item_name}, Type: {$item->item_type}, Location: {$item->location}\n";
}

echo "\n4. Items that should be retail inventory:\n";
$retailItems = Inventory::where('location', 'retail')->get(['id', 'item_name', 'location', 'item_type']);
foreach ($retailItems as $item) {
    echo "ID: {$item->id}, Name: {$item->item_name}, Type: {$item->item_type}, Location: {$item->location}\n";
}

echo "\n5. Total inventory count: " . Inventory::count() . "\n";

echo "\n=== CODE ANALYSIS ===\n";
echo "Based on the code analysis, the expected locations are:\n";
echo "- 'bakery' (used in OrderProcessingController, SupplierOrderController, OrderController)\n";
echo "- 'retail' (used in RetailInventoryController, RetailerOrderController)\n";
echo "- 'Bakery Storage' (used in RetailerOrderController for bakery inventory lookup)\n";
echo "- 'supplier' (used in OrderController for supplier inventory)\n";
