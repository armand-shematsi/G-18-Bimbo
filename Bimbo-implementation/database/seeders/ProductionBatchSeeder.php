<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductionBatch;
use Carbon\Carbon;

class ProductionBatchSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        ProductionBatch::truncate();

        // Create sample production batches with different statuses
        ProductionBatch::create([
            'name' => 'White Bread Batch #1',
            'status' => 'active',
            'scheduled_start' => Carbon::today()->setTime(8, 0),
            'actual_start' => Carbon::today()->setTime(8, 15),
            'quantity' => 100,
            'notes' => 'Morning white bread production',
        ]);

        ProductionBatch::create([
            'name' => 'Whole Wheat Batch #1',
            'status' => 'completed',
            'scheduled_start' => Carbon::today()->setTime(6, 0),
            'actual_start' => Carbon::today()->setTime(6, 10),
            'actual_end' => Carbon::today()->setTime(10, 30),
            'quantity' => 80,
            'notes' => 'Early morning whole wheat production',
        ]);

        ProductionBatch::create([
            'name' => 'Baguette Batch #1',
            'status' => 'planned',
            'scheduled_start' => Carbon::today()->setTime(14, 0),
            'quantity' => 60,
            'notes' => 'Afternoon baguette production',
        ]);

        ProductionBatch::create([
            'name' => 'Sourdough Batch #1',
            'status' => 'active',
            'scheduled_start' => Carbon::today()->setTime(10, 0),
            'actual_start' => Carbon::today()->setTime(10, 5),
            'quantity' => 50,
            'notes' => 'Sourdough bread production',
        ]);

        ProductionBatch::create([
            'name' => 'Rye Bread Batch #1',
            'status' => 'completed',
            'scheduled_start' => Carbon::yesterday()->setTime(8, 0),
            'actual_start' => Carbon::yesterday()->setTime(8, 5),
            'actual_end' => Carbon::yesterday()->setTime(12, 0),
            'quantity' => 70,
            'notes' => 'Yesterday rye bread production',
        ]);
    }
}
