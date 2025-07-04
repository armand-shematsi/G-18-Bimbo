<?php

namespace Database\Seeders;

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
        DB::table('products')->truncate();
        $now = Carbon::now();
        DB::table('products')->insert([
            ['name' => 'White Bread', 'unit_price' => 2.50, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Whole Wheat Bread', 'unit_price' => 3.00, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Multigrain Bread', 'unit_price' => 3.50, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sourdough Bread', 'unit_price' => 4.00, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Rye Bread', 'unit_price' => 3.75, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Baguette', 'unit_price' => 2.80, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Brioche', 'unit_price' => 3.20, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ciabatta', 'unit_price' => 3.10, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Focaccia', 'unit_price' => 3.00, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Potato Bread', 'unit_price' => 2.90, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Honey Oat Bread', 'unit_price' => 3.40, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Milk Bread', 'unit_price' => 2.70, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Challah', 'unit_price' => 3.60, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pita Bread', 'unit_price' => 2.20, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bagel', 'unit_price' => 1.50, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'English Muffin', 'unit_price' => 1.80, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Dinner Roll', 'unit_price' => 1.20, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cinnamon Swirl Bread', 'unit_price' => 3.80, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Gluten-Free Bread', 'unit_price' => 4.50, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Rustic Country Loaf', 'unit_price' => 4.20, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
