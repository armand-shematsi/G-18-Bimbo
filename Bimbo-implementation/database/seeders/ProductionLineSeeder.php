<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductionLine;

class ProductionLineSeeder extends Seeder
{
    public function run()
    {
        ProductionLine::query()->delete();
        $lines = [
            ['name' => 'Line A – Sourdough & Artisan', 'status' => 'Running', 'current_product' => 'Sourdough Bread', 'output' => 450, 'efficiency' => 95],
            ['name' => 'Line B – Whole Wheat & Multigrain', 'status' => 'Running', 'current_product' => 'Whole Wheat Bread', 'output' => 400, 'efficiency' => 93],
            ['name' => 'Line C – White & Rye', 'status' => 'Running', 'current_product' => 'White Bread', 'output' => 420, 'efficiency' => 94],
            ['name' => 'Line D – Baguette & Ciabatta', 'status' => 'Maintenance', 'current_product' => 'Baguette', 'output' => 0, 'efficiency' => null],
            ['name' => 'Line E – Brioche & Sweet Breads', 'status' => 'Running', 'current_product' => 'Brioche', 'output' => 380, 'efficiency' => 92],
            ['name' => 'Line F – Rolls & Buns', 'status' => 'Running', 'current_product' => 'Dinner Roll', 'output' => 390, 'efficiency' => 91],
            ['name' => 'Line G – Specialty & Gluten-Free', 'status' => 'Running', 'current_product' => 'Gluten-Free Bread', 'output' => 200, 'efficiency' => 89],
            ['name' => 'Line H – Bagels & Muffins', 'status' => 'Running', 'current_product' => 'Bagel', 'output' => 300, 'efficiency' => 90],
        ];
        foreach ($lines as $line) {
            ProductionLine::create($line);
        }
    }
}
