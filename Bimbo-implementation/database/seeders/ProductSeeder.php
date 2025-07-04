<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            'Sourdough Bread',
            'Whole Wheat Bread',
            'Multigrain Bread',
            'White Bread',
            'Rye Bread',
            'Baguette',
            'Brioche',
            'Ciabatta',
            'Focaccia',
            'Potato Bread',
            'Honey Oat Bread',
            'Milk Bread',
            'Challah',
            'Pita Bread',
            'Bagel',
            'English Muffin',
            'Dinner Roll',
            'Cinnamon Swirl Bread',
            'Gluten-Free Bread',
            'Rustic Country Loaf',
        ];
        foreach ($products as $name) {
            Product::firstOrCreate(['name' => $name]);
        }
    }
}
