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
            StaffSeeder::class,
            SupplyCenterSeeder::class,
            UserSeeder::class,
            AdminUserSeeder::class,
            VendorSeeder::class,
            ShiftSeeder::class,
            ProductSeeder::class,
            IngredientSeeder::class,
            ProductionBatchSeeder::class,
            ProductionLineSeeder::class,
            CustomerUserSeeder::class,
        ]);

        // Backfill product_id for inventories
        \App\Models\Inventory::query()->each(function($inventory) {
            $product = \App\Models\Product::where('name', $inventory->item_name)->first();
            if ($product) {
                $inventory->product_id = $product->id;
                $inventory->save();
                echo "Updated inventory #{$inventory->id} with product_id {$product->id}\n";
            } else {
                echo "No product found for inventory #{$inventory->id} ({$inventory->item_name})\n";
            }
        });

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
