<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductionBatch;
use Carbon\Carbon;

class ProductionBatchSeeder extends Seeder
{
    public function run()
    {
        $startOfWeek = Carbon::now()->subDays(6);
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            ProductionBatch::create([
                'name' => 'Batch ' . chr(65 + $i),
                'status' => 'Completed',
                'scheduled_start' => $date->copy()->setTime(6, 0),
                'actual_start' => $date->copy()->setTime(6, 30),
                'actual_end' => $date->copy()->setTime(14, 0),
                'notes' => 'Demo batch for trends',
            ]);
        }
    }
}
