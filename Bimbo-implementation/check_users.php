<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Current users and their roles:\n";
$users = DB::table('users')->select('id', 'name', 'email', 'role')->get();
foreach ($users as $user) {
    echo $user->id . " - " . $user->name . " - " . $user->role . "\n";
}

echo "\nUpdating user with ID 8 to have 'customer' role...\n";
DB::table('users')->where('id', 8)->update(['role' => 'customer']);

echo "Updated successfully!\n";

echo "\nUpdated users and their roles:\n";
$users = DB::table('users')->select('id', 'name', 'email', 'role')->get();
foreach ($users as $user) {
    echo $user->id . " - " . $user->name . " - " . $user->role . "\n";
} 