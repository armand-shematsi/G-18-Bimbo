<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateForecast extends Command
{
    protected $signature = 'forecast:update';
    protected $description = 'Export orders, clean data, and update ML forecast';

    public function handle()
    {
        $this->call('orders:export-csv');
        $this->info('Exported orders.');

        // Run Python scripts
        $mlPath = base_path('ml');
        $cleanCmd = "cd {$mlPath} && python clean_data.py";
        $forecastCmd = "cd {$mlPath} && python product_demand_forecast.py";

        $this->info(shell_exec($cleanCmd));
        $this->info(shell_exec($forecastCmd));

        $this->info('Forecast updated!');
    }
}
