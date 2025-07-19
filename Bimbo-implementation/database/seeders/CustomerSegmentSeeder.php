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
                ['CustomerID' => $data['Customer_ID']],
                [
                    'CustomerID' => $data['Customer_ID'],
                    'Name' => 'Customer ' . $data['Customer_ID'], // Generate name from ID
                    'PurchaseFrequency' => (int)$data['Purchase_Frequency'],
                    'AvgSpending' => (float)$data['Avg_Order_Value'],
                    'Location' => $data['Location'],
                    'PreferredBreadType' => $data['Bread_Type'],
                    'Segment' => isset($data['segment']) ? (int)$data['segment'] : null,
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
