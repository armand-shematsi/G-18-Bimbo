<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSegmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = base_path('ml/customer_segments_detailed.csv');

        if (!file_exists($csvPath)) {
            $this->command->error('CSV file not found: ' . $csvPath);
            return;
        }

        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file); // Skip header row

        $count = 0;
        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);

            DB::table('customer_segments')->updateOrInsert(
                ['CustomerID' => $data['customer_id']],
                [
                    'CustomerID' => $data['customer_id'],
                    'Name' => 'Customer ' . $data['customer_id'], // Generate name from ID
                    'PurchaseFrequency' => (int)$data['purchase_frequency'],
                    'AvgSpending' => (float)$data['avg_spending'],
                    'Location' => $data['location'],
                    'PreferredBreadType' => $data['preferred_bread'],
                    'Segment' => (int)$data['segment'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            $count++;
        }

        fclose($file);
        $this->command->info("Imported {$count} customer segments");
    }
}
