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
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Delete from child table first
        DB::table('production_batches')->delete();
        // Then delete from products
        DB::table('products')->delete();
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $now = Carbon::now();
        DB::table('products')->insert([
            ['name' => 'White Bread', 'unit_price' => 2.50, 'image_url' => 'images/white_bread.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Whole Wheat Bread', 'unit_price' => 3.00, 'image_url' => 'images/whole_wheat_bread.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Multigrain Bread', 'unit_price' => 3.50, 'image_url' => 'images/multigrain_bread.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sourdough Bread', 'unit_price' => 4.00, 'image_url' => 'images/sourdough_bread.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Rye Bread', 'unit_price' => 3.75, 'image_url' => 'images/rye_bread.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Baguette', 'unit_price' => 2.80, 'image_url' => 'images/baguette.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Brioche', 'unit_price' => 3.20, 'image_url' => 'images/brioche.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ciabatta', 'unit_price' => 3.10, 'image_url' => 'images/ciabatta.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Focaccia', 'unit_price' => 3.00, 'image_url' => 'images/focacia.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Potato Bread', 'unit_price' => 2.90, 'image_url' => 'images/potato_bread.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Honey Oat Bread', 'unit_price' => 3.40, 'image_url' => 'images/honey_oat_bread.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Milk Bread', 'unit_price' => 2.70, 'image_url' => 'images/milk_bread.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Challah', 'unit_price' => 3.60, 'image_url' => 'images/challah.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pita Bread', 'unit_price' => 2.20, 'image_url' => 'images/pita_bread.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bagel', 'unit_price' => 1.50, 'image_url' => 'images/bagel.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'English Muffin', 'unit_price' => 1.80, 'image_url' => 'images/english_muffins.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Dinner Roll', 'unit_price' => 1.20, 'image_url' => 'images/dinner_roll.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cinnamon Swirl Bread', 'unit_price' => 3.80, 'image_url' => 'images/cinnamon_swirl_bread.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Gluten-Free Bread', 'unit_price' => 4.50, 'image_url' => 'images/gluten_free_bread.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Rustic Country Loaf', 'unit_price' => 4.20, 'image_url' => null, 'created_at' => $now, 'updated_at' => $now],
            // Raw materials (ingredients) with images
            ['name' => 'Flour', 'unit_price' => 1.00, 'image_url' => 'images/flour.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Yeast', 'unit_price' => 1.00, 'image_url' => 'images/yeast.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Salt', 'unit_price' => 1.00, 'image_url' => 'images/salt.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sugar', 'unit_price' => 1.00, 'image_url' => 'images/sugar.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Butter', 'unit_price' => 1.00, 'image_url' => 'images/butter.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Eggs', 'unit_price' => 1.00, 'image_url' => 'images/eggs.jpg', 'created_at' => $now, 'updated_at' => $now],
            // Milk could be 'milk powder.jpg' if that's the intended image
            ['name' => 'Milk', 'unit_price' => 1.00, 'image_url' => 'images/milk powder.jpg', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
