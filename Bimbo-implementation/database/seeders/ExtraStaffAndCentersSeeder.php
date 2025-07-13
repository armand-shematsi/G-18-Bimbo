<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SupplyCenter;
use Illuminate\Support\Facades\Hash;

class ExtraStaffAndCentersSeeder extends Seeder
{
    public function run()
    {
        // Add 5 supply centers
        $centers = [
            ['name' => 'Central Supply Depot', 'location' => 'Central City'],
            ['name' => 'Northwest Hub', 'location' => 'Northwest District'],
            ['name' => 'Southeast Distribution', 'location' => 'Southeast Area'],
            ['name' => 'Uptown Center', 'location' => 'Uptown'],
            ['name' => 'Lakeside Supply', 'location' => 'Lakeside']
        ];
        foreach ($centers as $center) {
            SupplyCenter::firstOrCreate(['name' => $center['name']], $center);
        }

        // Add 5 staff
        $staff = [
            ['name' => 'John Doe', 'email' => 'john.doe@example.com'],
            ['name' => 'Jane Smith', 'email' => 'jane.smith@example.com'],
            ['name' => 'Carlos Rivera', 'email' => 'carlos.rivera@example.com'],
            ['name' => 'Amina Yusuf', 'email' => 'amina.yusuf@example.com'],
            ['name' => 'Wei Zhang', 'email' => 'wei.zhang@example.com'],
        ];
        foreach ($staff as $person) {
            User::firstOrCreate(
                ['email' => $person['email']],
                [
                    'name' => $person['name'],
                    'password' => Hash::make('password'),
                    'role' => 'staff',
                ]
            );
        }
    }
}
