<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class ExportOrdersToCsv extends Command
{
    protected $signature = 'orders:export-csv';
    protected $description = 'Export all orders to ml/large_sales.csv for ML forecasting';

    public function handle()
    {
        $orders = Order::with(['items.product'])->get();

        $data = [];
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $data[] = [
                    'Date' => $order->created_at ? $order->created_at->toDateString() : '',
                    'ProductType' => $item->product->name ?? 'Unknown',
                    'QuantitySold' => $item->quantity,
                    'Location' => $order->location ?? 'Unknown',
                ];
            }
        }

        $csvPath = base_path('ml/large_sales.csv');
        $fp = fopen($csvPath, 'w');
        if (!empty($data)) {
            fputcsv($fp, array_keys($data[0]));
            foreach ($data as $row) {
                fputcsv($fp, $row);
            }
        }
        fclose($fp);

        $this->info('Orders exported to ml/large_sales.csv');
    }
} 