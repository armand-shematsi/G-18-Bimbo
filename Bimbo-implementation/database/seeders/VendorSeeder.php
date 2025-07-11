<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            [
                'name' => 'Bimbo Bakeries USA',
                'email' => 'contact@bimbobakeries.com',
                'phone' => '+1 (800) 555-0123',
                'address' => '123 Baker Street',
                'city' => 'Fort Worth',
                'state' => 'TX',
                'zip_code' => '76102',
                'business_type' => 'bakery',
                'tax_id' => 'TAX123456',
                'business_license' => 'LIC123456',
                'status' => 'active',
                'sales' => 100000.00,
                'annual_revenue' => 500000.00,
                'regulatory_certification' => 'FDA Approved',
                'visit_scheduled' => null,
                'approved_at' => now(),
            ],
            [
                'name' => 'Sara Lee Bakery',
                'email' => 'info@saralee.com',
                'phone' => '+1 (800) 555-0124',
                'address' => '456 Bread Avenue',
                'city' => 'Chicago',
                'state' => 'IL',
                'zip_code' => '60601',
                'business_type' => 'bakery',
                'tax_id' => 'TAX654321',
                'business_license' => 'LIC654321',
                'status' => 'active',
                'sales' => 80000.00,
                'annual_revenue' => 400000.00,
                'regulatory_certification' => 'FDA Approved',
                'visit_scheduled' => null,
                'approved_at' => now(),
            ],
            [
                'name' => 'Wonder Bread',
                'email' => 'sales@wonderbread.com',
                'phone' => '+1 (800) 555-0125',
                'address' => '789 Flour Road',
                'city' => 'Kansas City',
                'state' => 'MO',
                'zip_code' => '64111',
                'business_type' => 'bakery',
                'tax_id' => 'TAX789012',
                'business_license' => 'LIC789012',
                'status' => 'active',
                'sales' => 90000.00,
                'annual_revenue' => 450000.00,
                'regulatory_certification' => 'FDA Approved',
                'visit_scheduled' => null,
                'approved_at' => now(),
            ],
            [
                'name' => 'Hostess Brands',
                'email' => 'contact@hostess.com',
                'phone' => '+1 (800) 555-0126',
                'address' => '321 Sweet Street',
                'city' => 'Lenexa',
                'state' => 'KS',
                'zip_code' => '66219',
                'business_type' => 'bakery',
                'tax_id' => 'TAX345678',
                'business_license' => 'LIC345678',
                'status' => 'active',
                'sales' => 95000.00,
                'annual_revenue' => 470000.00,
                'regulatory_certification' => 'FDA Approved',
                'visit_scheduled' => null,
                'approved_at' => now(),
            ],
            [
                'name' => "Entenmann's Bakery",
                'email' => 'info@entenmanns.com',
                'phone' => '+1 (800) 555-0127',
                'address' => '654 Pastry Lane',
                'city' => 'Bay Shore',
                'state' => 'NY',
                'zip_code' => '11706',
                'business_type' => 'bakery',
                'tax_id' => 'TAX567890',
                'business_license' => 'LIC567890',
                'status' => 'active',
                'sales' => 85000.00,
                'annual_revenue' => 420000.00,
                'regulatory_certification' => 'FDA Approved',
                'visit_scheduled' => null,
                'approved_at' => now(),
            ],
        ];

        foreach ($vendors as $vendor) {
            Vendor::updateOrCreate(
                ['email' => $vendor['email']],
                $vendor
            );
        }
    }
}
