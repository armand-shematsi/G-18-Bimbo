<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SupplyCentersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\SupplyCenter::insert([
            ['name' => 'PRODUCTION UNITS'],
            ['name' => 'PACKAGING UNITS'],
            ['name' => 'WAREHOUSES'],
            ['name' => 'DISTRIBUTION UNITS'],
            ['name' => 'RETAIL OUTLETS'],
        ]);
    }
} 