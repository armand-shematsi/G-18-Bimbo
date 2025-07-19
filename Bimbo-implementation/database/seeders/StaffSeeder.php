<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    public function run()
    {
        $staff = [
            ['name' => 'Alice Baker', 'role' => 'baker', 'status' => 'Present'],
            ['name' => 'Bob Driver', 'role' => 'driver', 'status' => 'Present'],
            ['name' => 'Charlie Loader', 'role' => 'loader', 'status' => 'Absent'],
            ['name' => 'Diana Manager', 'role' => 'manager', 'status' => 'Present'],
            ['name' => 'Eve Staff', 'role' => 'staff', 'status' => 'Absent'],
            ['name' => 'ISAAC', 'role' => 'baker', 'status' => 'Present'],
            ['name' => 'ISCO', 'role' => 'store keeper', 'status' => 'Present'],
            ['name' => 'HELEN', 'role' => 'packager', 'status' => 'Present'],
            ['name' => 'ROY', 'role' => 'driver', 'status' => 'Present'],
            ['name' => 'HANES', 'role' => 'seller', 'status' => 'Present'],
            ['name' => 'ARMAND', 'role' => 'driver', 'status' => 'Present'],
        ];
        foreach ($staff as $s) {
            Staff::updateOrCreate(
                ['name' => $s['name'], 'role' => $s['role']],
                ['status' => $s['status']]
            );
        }
    }
}
