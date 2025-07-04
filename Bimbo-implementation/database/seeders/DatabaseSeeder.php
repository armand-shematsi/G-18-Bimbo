<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            UserSeeder::class,
            AdminUserSeeder::class,
            VendorSeeder::class,
            ShiftSeeder::class,
            ProductSeeder::class,
            IngredientSeeder::class,
            ProductionBatchSeeder::class,
            ProductionLineSeeder::class,
<<<<<<< HEAD
            CustomerUserSeeder::class,
            ProductSeeder::class,
=======
>>>>>>> f5a25215d73e2f9a3f3da1c53e636dcedbde3c14
        ]);

        // --- Demo Attendance for Workforce Overview ---
        $today = now()->toDateString();
        $staff = \App\Models\User::where('role', 'staff')->get();
        $presentStaff = $staff->take(2); // 2 present
        $absentStaff = $staff->slice(2); // rest absent
        foreach ($presentStaff as $user) {
            \App\Models\Attendance::updateOrCreate([
                'user_id' => $user->id,
                'date' => $today,
            ], [
                'status' => 'present',
            ]);
        }
        foreach ($absentStaff as $user) {
            \App\Models\Attendance::updateOrCreate([
                'user_id' => $user->id,
                'date' => $today,
            ], [
                'status' => 'absent',
            ]);
        }
    }
}
