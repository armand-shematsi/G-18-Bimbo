<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SupplyCenter;

class SupplyCentersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $centers = [
            [
                'name' => 'Production Units',
                'type' => 'production',
                'location' => 'Industrial Area',
                'required_role' => 'baker',
                'shift_time' => '8:00AM-5:00PM',
                'required_staff_count' => 10,
            ],
            [
                'name' => 'Packaging Units',
                'type' => 'packaging',
                'location' => 'Zone 2',
                'required_role' => 'packager',
                'shift_time' => '8:00AM-5:00PM',
                'required_staff_count' => 6,
            ],
            [
                'name' => 'Warehouses',
                'type' => 'warehouse',
                'location' => 'Warehouse District',
                'required_role' => 'store keeper',
                'shift_time' => '8:00AM-5:00PM',
                'required_staff_count' => 8,
            ],
            [
                'name' => 'Distribution Units',
                'type' => 'distribution',
                'location' => 'City Outskirts',
                'required_role' => 'driver',
                'shift_time' => '8:00AM-5:00PM',
                'required_staff_count' => 5,
            ],
            [
                'name' => 'Retail Outlets',
                'type' => 'retail',
                'location' => 'Downtown',
                'required_role' => 'seller',
                'shift_time' => '9:00AM-6:00PM',
                'required_staff_count' => 3,
            ],
        ];
        foreach ($centers as $center) {
            SupplyCenter::create($center);
        }
    }
}
