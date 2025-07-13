<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class RealDataSeeder extends Seeder
{
    public function run(): void
    {
        // Wipe all users and supply centers
        DB::table('staff_supply_center_assignments')->truncate();
        DB::table('users')->truncate();
        DB::table('supply_centers')->truncate();

        // Insert supply centers
        $centers = [
            ['name' => 'Main bakery', 'location' => 'HQ', 'required_role' => 'admin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'North supply hub', 'location' => 'North', 'required_role' => 'supplier', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'East hub', 'location' => 'East', 'required_role' => 'retail_manager', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'South hub', 'location' => 'South', 'required_role' => 'bakery_manager', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Central hub', 'location' => 'Central', 'required_role' => 'distributor', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];
        DB::table('supply_centers')->insert($centers);

        // Insert users
        $users = [
            ['name' => 'Admin user', 'email' => 'admin@example.com', 'role' => 'admin', 'password' => Hash::make('password'), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Supplier user', 'email' => 'supplier@example.com', 'role' => 'supplier', 'password' => Hash::make('password'), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Retailer user', 'email' => 'retailer@example.com', 'role' => 'retail_manager', 'password' => Hash::make('password'), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Bakery user', 'email' => 'bakery@example.com', 'role' => 'bakery_manager', 'password' => Hash::make('password'), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Distributor user', 'email' => 'distributor@example.com', 'role' => 'distributor', 'password' => Hash::make('password'), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];
        DB::table('users')->insert($users);

        // Assign each staff to their supply center
        $userIds = DB::table('users')->pluck('id', 'role');
        $centerIds = DB::table('supply_centers')->pluck('id', 'required_role');
        $today = Carbon::now()->toDateString();
        $assignments = [
            ['user_id' => $userIds['admin'], 'supply_center_id' => $centerIds['admin'], 'assigned_date' => $today, 'status' => 'assigned', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => $userIds['supplier'], 'supply_center_id' => $centerIds['supplier'], 'assigned_date' => $today, 'status' => 'assigned', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => $userIds['retail_manager'], 'supply_center_id' => $centerIds['retail_manager'], 'assigned_date' => $today, 'status' => 'assigned', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => $userIds['bakery_manager'], 'supply_center_id' => $centerIds['bakery_manager'], 'assigned_date' => $today, 'status' => 'assigned', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => $userIds['distributor'], 'supply_center_id' => $centerIds['distributor'], 'assigned_date' => $today, 'status' => 'assigned', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];
        DB::table('staff_supply_center_assignments')->insert($assignments);
    }
}
