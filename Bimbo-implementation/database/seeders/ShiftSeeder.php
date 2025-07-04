<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;

class ShiftSeeder extends Seeder
{
    public function run()
    {
        $users = User::take(3)->get(); // Use first 3 users for demo
        $startOfWeek = Carbon::now()->startOfWeek();
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            // Create 2 filled shifts per day
            foreach ($users as $user) {
                Shift::create([
                    'user_id' => $user->id,
                    'production_batch_id' => null,
                    'start_time' => $date->copy()->setTime(8, 0),
                    'end_time' => $date->copy()->setTime(16, 0),
                    'role' => $user->role,
                ]);
            }
            // Create 1 unfilled shift per day
            Shift::create([
                'user_id' => null,
                'production_batch_id' => null,
                'start_time' => $date->copy()->setTime(16, 0),
                'end_time' => $date->copy()->setTime(20, 0),
                'role' => 'worker',
            ]);
            // Add Distributor and Retail Manager demo shifts
            $distributor = User::where('role', 'distributor')->first();
            $retail = User::where('role', 'retail_manager')->first();
            if ($distributor) {
                Shift::create([
                    'user_id' => $distributor->id,
                    'production_batch_id' => null,
                    'start_time' => $date->copy()->setTime(9, 0),
                    'end_time' => $date->copy()->setTime(17, 0),
                    'role' => 'distributor',
                ]);
            }
            if ($retail) {
                Shift::create([
                    'user_id' => $retail->id,
                    'production_batch_id' => null,
                    'start_time' => $date->copy()->setTime(10, 0),
                    'end_time' => $date->copy()->setTime(18, 0),
                    'role' => 'retail_manager',
                ]);
            }
        }
        // Ensure at least one shift is assigned to a batch for today
        $batch = \App\Models\ProductionBatch::whereDate('scheduled_start', now()->toDateString())->first();
        $staff = \App\Models\User::where('role', 'staff')->first();
        if ($batch && $staff) {
            Shift::create([
                'user_id' => $staff->id,
                'production_batch_id' => $batch->id,
                'start_time' => now()->setTime(8, 0),
                'end_time' => now()->setTime(16, 0),
                'role' => 'staff',
            ]);
        }
    }
}
