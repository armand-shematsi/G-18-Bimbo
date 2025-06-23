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
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@bimbo.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Supplier User
        User::create([
            'name' => 'Supplier User',
            'email' => 'supplier@bimbo.com',
            'password' => Hash::make('supplier123'),
            'role' => 'supplier',
        ]);

        // Bakery Manager
        User::create([
            'name' => 'Bakery Manager',
            'email' => 'bakery@bimbo.com',
            'password' => Hash::make('bakery123'),
            'role' => 'bakery_manager',
        ]);

        // Distributor
        User::create([
            'name' => 'Distributor User',
            'email' => 'distributor@bimbo.com',
            'password' => Hash::make('distributor123'),
            'role' => 'distributor',
        ]);

        // Retail Manager
        User::create([
            'name' => 'Retail Manager',
            'email' => 'retail@bimbo.com',
            'password' => Hash::make('retail123'),
            'role' => 'retail_manager',
        ]);
    }
} 