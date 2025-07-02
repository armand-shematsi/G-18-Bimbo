<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductionLine;

class ProductionLineSeeder extends Seeder
{
    public function run()
    {
        ProductionLine::truncate();
        ProductionLine::create([
            'name' => 'Line 1',
            'status' => 'Running',
            'current_product' => 'White Bread',
            'output' => 450,
            'efficiency' => 95,
        ]);
        ProductionLine::create([
            'name' => 'Line 2',
            'status' => 'Maintenance',
            'current_product' => 'Whole Wheat',
            'output' => 0,
            'efficiency' => null,
        ]);
        ProductionLine::create([
            'name' => 'Line 3',
            'status' => 'Running',
            'current_product' => 'Sourdough',
            'output' => 380,
            'efficiency' => 92,
        ]);
    }
} 