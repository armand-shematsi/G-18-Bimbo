<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run()
    {
        $staff = [
            ['name' => 'Alice Baker', 'email' => 'alice.baker@example.com', 'role' => 'baker'],
            ['name' => 'Bob Driver', 'email' => 'bob.driver@example.com', 'role' => 'driver'],
            ['name' => 'Charlie Loader', 'email' => 'charlie.loader@example.com', 'role' => 'loader'],
            ['name' => 'Diana Manager', 'email' => 'diana.manager@example.com', 'role' => 'manager'],
            ['name' => 'Eve Staff', 'email' => 'eve.staff@example.com', 'role' => 'staff'],
        ];
        foreach ($staff as $s) {
            User::updateOrCreate(
                ['email' => $s['email']],
                [
                    'name' => $s['name'],
                    'role' => $s['role'],
                    'password' => Hash::make('password'),
                ]
            );
        }
    }
}
