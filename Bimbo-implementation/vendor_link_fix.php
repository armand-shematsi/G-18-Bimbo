<?php

use App\Models\User;
use App\Models\Vendor;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Bootstrap Laravel
$kernel->bootstrap();

// Find all supplier users
$suppliers = User::where('role', 'supplier')->get();
foreach ($suppliers as $user) {
    $vendor = Vendor::where('email', $user->email)->first();
    if ($vendor) {
        $vendor->user_id = $user->id;
        $vendor->save();
        echo "Linked vendor {$vendor->id} to user {$user->id}\n";
    } else {
        // Create a vendor for this user
        $vendor = Vendor::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '',
            'address' => $user->address ?? '',
            'status' => 'active',
        ]);
        echo "Created vendor {$vendor->id} for user {$user->id}\n";
    }
}

echo "Vendor linking complete.\n"; 