<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductionBatch;
use App\Models\Product;
use App\Models\ProductionLine;
use Carbon\Carbon;

class ProductionBatchSeeder extends Seeder
{
    public function run()
    {
        $products = Product::pluck('name')->toArray();
        $today = Carbon::today();

        $am_hours = [9, 10, 11];
        $pm_hours = [15, 16, 17];

        $lines = ProductionLine::all()->keyBy(function ($line) {
            return strtolower(explode('–', $line->name)[1] ?? $line->name);
        });

        if (count($products) === 0) {
            // AM batch
            ProductionBatch::create([
                'name' => 'Demo Batch Active',
                'status' => 'Active',
                'scheduled_start' => $today->copy()->setTime($am_hours[0], 0),
                'actual_start' => $today->copy()->setTime($am_hours[0], 15),
                'actual_end' => null,
                'notes' => 'Demo active batch',
                'production_line_id' => $lines->first() ? $lines->first()->id : null,
            ]);
            // PM batch
            ProductionBatch::create([
                'name' => 'Demo Batch Completed',
                'status' => 'Completed',
                'scheduled_start' => $today->copy()->setTime($pm_hours[0], 0),
                'actual_start' => $today->copy()->setTime($pm_hours[0], 15),
                'actual_end' => $today->copy()->setTime($pm_hours[0], 45),
                'notes' => 'Demo completed batch',
                'production_line_id' => $lines->last() ? $lines->last()->id : null,
            ]);
        } else {
            foreach ($products as $i => $name) {
                $is_am = $i % 2 === 0;
                $hour = $is_am
                    ? $am_hours[$i % count($am_hours)]
                    : $pm_hours[$i % count($pm_hours)];
                // Try to match bread type to line
                $lineId = null;
                $lower = strtolower($name);
                foreach ($lines as $key => $line) {
                    if (strpos($lower, trim($key)) !== false) {
                        $lineId = $line->id;
                        break;
                    }
                }
                if (!$lineId && $lines->count()) {
                    $lineId = $lines->random()->id;
                }
                ProductionBatch::create([
                    'name' => $name,
                    'status' => $i === 0 ? 'Active' : 'Completed',
                    'scheduled_start' => $today->copy()->setTime($hour, 0),
                    'actual_start' => $today->copy()->setTime($hour, 15),
                    'actual_end' => $i === 0 ? null : $today->copy()->setTime($hour, 45),
                    'notes' => 'Demo batch for ' . $name,
                    'production_line_id' => $lineId,
                ]);
            }
            // Ensure at least one completed batch in PM
            if (count($products) == 1) {
                ProductionBatch::create([
                    'name' => $products[0] . ' Completed',
                    'status' => 'Completed',
                    'scheduled_start' => $today->copy()->setTime($pm_hours[1], 0),
                    'actual_start' => $today->copy()->setTime($pm_hours[1], 15),
                    'actual_end' => $today->copy()->setTime($pm_hours[1], 45),
                    'notes' => 'Extra completed batch for demo',
                    'production_line_id' => $lines->random()->id ?? null,
                ]);
            }
        }
    }
}
