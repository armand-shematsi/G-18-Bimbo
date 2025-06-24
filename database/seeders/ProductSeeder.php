<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'White Bread',
                'category' => 'bread',
                'stock' => 100,
                'price' => 2.50,
                'status' => 'in_stock',
            ],
            [
                'name' => 'Whole Wheat Bread',
                'category' => 'bread',
                'stock' => 80,
                'price' => 2.75,
                'status' => 'in_stock',
            ],
            [
                'name' => 'Multigrain Bread',
                'category' => 'bread',
                'stock' => 60,
                'price' => 3.00,
                'status' => 'in_stock',
            ],
            [
                'name' => 'Chocolate Cake',
                'category' => 'cakes',
                'stock' => 20,
                'price' => 15.00,
                'status' => 'in_stock',
            ],
            [
                'name' => 'Croissant',
                'category' => 'pastries',
                'stock' => 50,
                'price' => 1.50,
                'status' => 'in_stock',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
