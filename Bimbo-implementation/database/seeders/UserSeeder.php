<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@bimbo.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Supplier User
        User::updateOrCreate(
            ['email' => 'supplier@bimbo.com'],
            [
                'name' => 'Supplier User',
                'password' => Hash::make('supplier123'),
                'role' => 'supplier',
            ]
        );

        // Bakery Manager
        User::updateOrCreate(
            ['email' => 'bakery@bimbo.com'],
            [
                'name' => 'Bakery Manager',
                'password' => Hash::make('bakery123'),
                'role' => 'bakery_manager',
            ]
        );

        // Distributor
        User::updateOrCreate(
            ['email' => 'distributor@bimbo.com'],
            [
                'name' => 'Distributor User',
                'password' => Hash::make('distributor123'),
                'role' => 'distributor',
            ]
        );

        // Retail Manager
        User::updateOrCreate(
            ['email' => 'retail@bimbo.com'],
            [
                'name' => 'Retail Manager',
                'password' => Hash::make('retail123'),
                'role' => 'retail_manager',
            ]
        );

        // Customer User
        User::updateOrCreate(
            ['email' => 'customer@bimbo.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('customer123'),
                'role' => 'customer',
            ]
        );

        // Demo Staff Users
        $staff = [
            ['name' => 'Alice Baker', 'email' => 'alice@bakery.com', 'password' => Hash::make('password'), 'role' => 'staff'],
            ['name' => 'Bob Dough', 'email' => 'bob@bakery.com', 'password' => Hash::make('password'), 'role' => 'staff'],
            ['name' => 'Charlie Crust', 'email' => 'charlie@bakery.com', 'password' => Hash::make('password'), 'role' => 'staff'],
        ];
        foreach ($staff as $user) {
            User::firstOrCreate(['email' => $user['email']], $user);
        }
    }
}
