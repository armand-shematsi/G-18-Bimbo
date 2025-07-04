<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    public function run()
    {
        $ingredients = [
            ['name' => 'Flour', 'unit' => 'kg', 'stock_quantity' => 1000],
            ['name' => 'Yeast', 'unit' => 'g', 'stock_quantity' => 1000],
            ['name' => 'Water', 'unit' => 'L', 'stock_quantity' => 1000],
            ['name' => 'Salt', 'unit' => 'g', 'stock_quantity' => 1000],
            ['name' => 'Sugar', 'unit' => 'g', 'stock_quantity' => 1000],
            ['name' => 'Butter', 'unit' => 'g', 'stock_quantity' => 1000],
            ['name' => 'Eggs', 'unit' => 'pcs', 'stock_quantity' => 1000],
            ['name' => 'Milk', 'unit' => 'L', 'stock_quantity' => 1000],
        ];
        foreach ($ingredients as $ingredient) {
            Ingredient::firstOrCreate(['name' => $ingredient['name']], $ingredient);
        }
    }
}
 