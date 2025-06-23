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
                'status' => 'active',
            ],
            [
                'name' => 'Sara Lee Bakery',
                'email' => 'info@saralee.com',
                'phone' => '+1 (800) 555-0124',
                'address' => '456 Bread Avenue, Chicago, IL 60601',
                'status' => 'active',
            ],
            [
                'name' => 'Wonder Bread',
                'email' => 'sales@wonderbread.com',
                'phone' => '+1 (800) 555-0125',
                'address' => '789 Flour Road, Kansas City, MO 64111',
                'status' => 'active',
            ],
            [
                'name' => 'Hostess Brands',
                'email' => 'contact@hostess.com',
                'phone' => '+1 (800) 555-0126',
                'address' => '321 Sweet Street, Lenexa, KS 66219',
                'status' => 'active',
            ],
            [
                'name' => 'Entenmann\'s Bakery',
                'email' => 'info@entenmanns.com',
                'phone' => '+1 (800) 555-0127',
                'address' => '654 Pastry Lane, Bay Shore, NY 11706',
                'status' => 'active',
            ],
        ];

        foreach ($vendors as $vendor) {
            Vendor::create($vendor);
        }
    }
} 