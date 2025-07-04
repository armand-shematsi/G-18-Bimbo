<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CustomerUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Test Customer',
                'email' => 'customer@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ]
        );
    }
}