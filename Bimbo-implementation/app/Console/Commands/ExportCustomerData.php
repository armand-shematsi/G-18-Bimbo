<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ExportCustomerData extends Command
{
    protected $signature = 'customers:export-segmentation-data';
    protected $description = 'Export customer data for segmentation';

    public function handle()
    {
        $customers = User::where('role', 'customer')->with(['orders' => function($q) {
            $q->select('user_id', 'created_at', 'total');
        }])->get();

        $data = [];
        foreach ($customers as $customer) {
            $orders = $customer->orders;
            $recency = $orders->max('created_at') ? now()->diffInDays($orders->max('created_at')) : null;
            $frequency = $orders->count();
            $monetary = $orders->sum('total');
            $data[] = [
                'customer_id' => $customer->id,
                'purchase_frequency' => $frequency,
                'avg_spending' => $frequency ? $monetary / $frequency : 0,
                'total_spending' => $monetary,
                'last_purchase_days_ago' => $recency,
                // Add more fields as needed (e.g., preferred_bread, location)
            ];
        }

        $fp = fopen(storage_path('app/customer_purchase_data.csv'), 'w');
        if (!empty($data)) {
            fputcsv($fp, array_keys($data[0]));
            foreach ($data as $row) {
                fputcsv($fp, $row);
            }
        }
        fclose($fp);

        $this->info('Customer data exported to storage/app/customer_purchase_data.csv');
    }
} 