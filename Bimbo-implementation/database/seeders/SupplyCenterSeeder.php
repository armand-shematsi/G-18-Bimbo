<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupplyCenter;

class SupplyCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supplyCenters = [
            [
                'name' => 'Main Bakery Center',
                'location' => 'Downtown Area',
            ],
            [
                'name' => 'North Distribution Center',
                'location' => 'North District',
            ],
            [
                'name' => 'South Distribution Center',
                'location' => 'South District',
            ],
            [
                'name' => 'East Supply Hub',
                'location' => 'East District',
            ],
            [
                'name' => 'West Supply Hub',
                'location' => 'West District',
            ],
        ];

        foreach ($supplyCenters as $center) {
            SupplyCenter::create($center);
        }
    }
} 