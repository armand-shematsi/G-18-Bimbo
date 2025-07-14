<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inventory;
use App\Models\User;
use App\Models\Vendor;

// Get or create a supplier user
$supplier = User::where('role', 'supplier')->first();
if (!$supplier) {
    echo "No supplier user found. Please create one first.\n";
    exit;
}

// Get or create a vendor for the supplier
$vendor = Vendor::where('email', 'test@supplier.com')->first();
if (!$vendor) {
    $vendor = Vendor::create([
        'name' => 'Test Supplier Company',
        'email' => 'test@supplier.com',
        'phone' => '1234567890',
        'address' => '123 Test Street',
        'city' => 'Test City',
        'state' => 'Test State',
        'zip_code' => '12345',
        'business_type' => 'Supplier',
        'status' => 'active'
    ]);
    echo "Created vendor for supplier.\n";
}

// Create test inventory items
$inventoryItems = [
    [
        'item_name' => 'Wheat Flour',
        'item_type' => 'Raw Material',
        'quantity' => 100,
        'unit' => 'kg',
        'status' => 'available',
        'reorder_level' => 20,
        'unit_price' => 2.50,
        'user_id' => $supplier->id
    ],
    [
        'item_name' => 'Sugar',
        'item_type' => 'Raw Material',
        'quantity' => 50,
        'unit' => 'kg',
        'status' => 'available',
        'reorder_level' => 10,
        'unit_price' => 3.00,
        'user_id' => $supplier->id
    ],
    [
        'item_name' => 'Yeast',
        'item_type' => 'Raw Material',
        'quantity' => 25,
        'unit' => 'kg',
        'status' => 'available',
        'reorder_level' => 5,
        'unit_price' => 8.00,
        'user_id' => $supplier->id
    ],
    [
        'item_name' => 'Salt',
        'item_type' => 'Raw Material',
        'quantity' => 15,
        'unit' => 'kg',
        'status' => 'low_stock',
        'reorder_level' => 10,
        'unit_price' => 1.50,
        'user_id' => $supplier->id
    ]
];

foreach ($inventoryItems as $itemData) {
    $existing = Inventory::where('item_name', $itemData['item_name'])
                        ->where('user_id', $supplier->id)
                        ->first();

    if (!$existing) {
        Inventory::create($itemData);
        echo "Created inventory item: {$itemData['item_name']}\n";
    } else {
        echo "Inventory item already exists: {$itemData['item_name']}\n";
    }
}

echo "\nTest data setup complete!\n";
echo "Supplier ID: {$supplier->id}\n";
echo "Vendor ID: {$vendor->id}\n";
echo "You can now test the order creation feature.\n";
