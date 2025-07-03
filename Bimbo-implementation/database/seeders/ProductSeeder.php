<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('products')->truncate();
        $now = Carbon::now();
        DB::table('products')->insert([
            ['name' => 'White Bread', 'unit_price' => 2.50, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Whole Wheat Bread', 'unit_price' => 3.00, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Multigrain Bread', 'unit_price' => 3.50, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sourdough Bread', 'unit_price' => 4.00, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Rye Bread', 'unit_price' => 3.75, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
