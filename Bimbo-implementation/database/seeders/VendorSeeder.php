<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;

class VendorSeeder extends Seeder
{
    public function run()
    {
        $suppliers = User::where('role', 'supplier')->get();
        foreach ($suppliers as $supplier) {
            Vendor::firstOrCreate(
                ['user_id' => $supplier->id],
                [
                    'name' => $supplier->name,
                    'email' => $supplier->email,
                    'phone' => $supplier->phone ?? '000-000-0000',
                    'address' => $supplier->address ?? 'Unknown Address',
                    'city' => $supplier->city ?? 'Unknown City',
                    'state' => $supplier->state ?? 'Unknown State',
                    'zip_code' => $supplier->zip_code ?? '00000',
                    'business_type' => 'Supplier',
                    'tax_id' => $supplier->tax_id ?? 'TAX000000',
                    'business_license' => $supplier->business_license ?? 'LIC000000',
                    'status' => 'active',
                    'sales' => 0,
                    'annual_revenue' => 0,
                    'years_in_business' => 0,
                    'regulatory_certification' => $supplier->regulatory_certification ?? null,
                ]
            );
        }
    }
}
