<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ImportCustomerSegments extends Command
{
    protected $signature = 'customers:import-segments';
    protected $description = 'Import customer segments from ML output';

    public function handle()
    {
        $file = storage_path('app/ml/customer_segments_detailed.csv'); // Adjust path if needed
        if (!file_exists($file)) {
            $this->error('Segment file not found!');
            return;
        }

        $rows = array_map('str_getcsv', file($file));
        $header = array_shift($rows);

        foreach ($rows as $row) {
            $data = array_combine($header, $row);
            User::where('id', $data['customer_id'])->where('role', 'customer')->update(['segment' => $data['segment']]);
        }

        $this->info('Customer segments imported successfully.');
    }
} 