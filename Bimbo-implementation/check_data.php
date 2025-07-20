<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DATABASE CHECK ===\n\n";

echo "VENDORS:\n";
$vendors = \App\Models\Vendor::all(['id', 'name']);
foreach ($vendors as $vendor) {
    echo "ID: {$vendor->id} - Name: {$vendor->name}\n";
}

echo "\nPRODUCTS:\n";
$products = \App\Models\Product::all(['id', 'name', 'unit_price']);
foreach ($products as $product) {
    echo "ID: {$product->id} - Name: {$product->name} - Price: \${$product->unit_price}\n";
}

echo "\nORDERS: " . \App\Models\Order::count() . "\n";
echo "ORDER ITEMS: " . \App\Models\OrderItem::count() . "\n";
