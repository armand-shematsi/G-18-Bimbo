<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SupplyCenter;
use App\Models\Shift;
use Carbon\Carbon;

class ShiftSeeder extends Seeder
{
    public function run()
    {
        $today = Carbon::today();
        $start = $today->copy()->setTime(8, 0, 0);
        $end = $today->copy()->setTime(16, 0, 0);
        $staff = User::all();
        foreach ($staff as $user) {
            // Try to match supply center by role
            $center = SupplyCenter::where('required_role', $user->role)->first();
            Shift::updateOrCreate([
                'user_id' => $user->id,
                'start_time' => $start,
            ], [
                'supply_center_id' => $center ? $center->id : null,
                'end_time' => $end,
                'role' => $user->role,
            ]);
        }
    }
}
