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
                'address' => '123 Baker Street, Fort Worth, TX 76102',
                'city' => 'Fort Worth',
                'state' => 'TX',
                'zip_code' => '76102',
                'business_type' => 'Bakery',
                'tax_id' => 'BBUSA-TX-001',
                'business_license' => 'LIC-BBUSA-TX-001',
                'status' => 'active',
            ],
            [
                'name' => 'Sara Lee Bakery',
                'email' => 'info@saralee.com',
                'phone' => '+1 (800) 555-0124',
                'address' => '456 Bread Avenue, Chicago, IL 60601',
                'city' => 'Chicago',
                'state' => 'IL',
                'zip_code' => '60601',
                'business_type' => 'Bakery',
                'tax_id' => 'SLB-IL-002',
                'business_license' => 'LIC-SLB-IL-002',
                'status' => 'active',
            ],
            [
                'name' => 'Wonder Bread',
                'email' => 'sales@wonderbread.com',
                'phone' => '+1 (800) 555-0125',
                'address' => '789 Flour Road, Kansas City, MO 64111',
                'city' => 'Kansas City',
                'state' => 'MO',
                'zip_code' => '64111',
                'business_type' => 'Bakery',
                'tax_id' => 'WB-MO-003',
                'business_license' => 'LIC-WB-MO-003',
                'status' => 'active',
            ],
            [
                'name' => 'Hostess Brands',
                'email' => 'contact@hostess.com',
                'phone' => '+1 (800) 555-0126',
                'address' => '321 Sweet Street, Lenexa, KS 66219',
                'city' => 'Lenexa',
                'state' => 'KS',
                'zip_code' => '66219',
                'business_type' => 'Bakery',
                'tax_id' => 'HB-KS-004',
                'business_license' => 'LIC-HB-KS-004',
                'status' => 'active',
            ],
            [
                'name' => 'Entenmann\'s Bakery',
                'email' => 'info@entenmanns.com',
                'phone' => '+1 (800) 555-0127',
                'address' => '654 Pastry Lane, Bay Shore, NY 11706',
                'city' => 'Bay Shore',
                'state' => 'NY',
                'zip_code' => '11706',
                'business_type' => 'Bakery',
                'tax_id' => 'EB-NY-005',
                'business_license' => 'LIC-EB-NY-005',
                'status' => 'active',
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