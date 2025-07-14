<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inventory;
use App\Models\User;

echo "Testing Inventory model...\n";

try {
    // Test if we can count inventory items
    $count = Inventory::count();
    echo "Inventory count: {$count}\n";

    // Test if we can get a supplier user
    $supplier = User::where('role', 'supplier')->first();
    if ($supplier) {
        echo "Found supplier user: {$supplier->id}\n";

        // Test if we can query inventory for this supplier
        $supplierInventory = Inventory::where('user_id', $supplier->id)->get();
        echo "Supplier inventory count: {$supplierInventory->count()}\n";

        if ($supplierInventory->count() > 0) {
            echo "Sample inventory item: {$supplierInventory->first()->item_name}\n";
        }
    } else {
        echo "No supplier user found\n";
    }

    echo "Inventory model is working correctly!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
