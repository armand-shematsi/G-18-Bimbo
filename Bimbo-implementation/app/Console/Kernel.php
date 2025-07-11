<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
        /**
         * Define the application's command schedule.
         */
        protected function schedule(Schedule $schedule): void
        {
                // Daily reports - sent every morning at 8:00 AM
                $schedule->command('reports:daily')
                        ->dailyAt('08:00')
                        ->withoutOverlapping()
                        ->runInBackground()
                        ->emailOutputTo('admin@bimbo.com');

                // Weekly reports - sent every Monday at 9:00 AM
                $schedule->command('reports:weekly')
                        ->weeklyOn(1, '09:00') // Monday at 9:00 AM
                        ->withoutOverlapping()
                        ->runInBackground()
                        ->emailOutputTo('admin@bimbo.com');

                // Inventory alerts - checked every 4 hours during business hours
                $schedule->command('reports:inventory-alert --threshold=10')
                        ->everyFourHours()
                        ->between('06:00', '22:00') // Only during business hours
                        ->withoutOverlapping()
                        ->runInBackground()
                        ->emailOutputTo('admin@bimbo.com');

                // Critical inventory alerts - checked every hour
                $schedule->command('reports:inventory-alert --threshold=5')
                        ->hourly()
                        ->between('06:00', '22:00')
                        ->withoutOverlapping()
                        ->runInBackground()
                        ->emailOutputTo('admin@bimbo.com');

                // Auto-assign staff to supply centers daily at 7:00 AM
                $schedule->command('workforce:auto-assign')
                        ->dailyAt('07:00')
                        ->withoutOverlapping()
                        ->runInBackground();

                // Clean up old report files - run daily at 2:00 AM
                $schedule->command('reports:cleanup')
                        ->dailyAt('02:00')
                        ->withoutOverlapping()
                        ->runInBackground();
        }

        /**
         * Register the commands for the application.
         */
        protected function commands(): void
        {
                $this->load(__DIR__ . '/Commands');

                require base_path('routes/console.php');
        }
}
