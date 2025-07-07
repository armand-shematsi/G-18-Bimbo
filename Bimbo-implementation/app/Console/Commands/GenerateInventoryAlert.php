<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ReportService;
use App\Notifications\InventoryAlertNotification;
use App\Models\User;

class GenerateInventoryAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:inventory-alert {--threshold=10 : Minimum stock threshold for alerts} {--stakeholder=all : Specific stakeholder type (admin, supplier, retail_manager)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and send inventory alerts for low stock items';

    /**
     * Execute the console command.
     */
    public function handle(ReportService $reportService)
    {
        $threshold = $this->option('threshold');
        $stakeholderType = $this->option('stakeholder');
        
        $this->info("Checking inventory levels (threshold: {$threshold})...");
        
        // Get users who should receive inventory alerts
        if ($stakeholderType === 'all') {
            $stakeholders = User::whereIn('role', ['admin', 'supplier', 'retail_manager'])
                               ->where('status', 'active')
                               ->get();
        } else {
            $stakeholders = User::where('role', $stakeholderType)
                               ->where('status', 'active')
                               ->get();
        }
        
        if ($stakeholders->isEmpty()) {
            $this->error("No active stakeholders found for role: {$stakeholderType}");
            return Command::FAILURE;
        }
        
        $lowStockItems = $reportService->getLowStockItems($threshold);
        
        if ($lowStockItems->isEmpty()) {
            $this->info('✅ No low stock items found. All inventory levels are healthy!');
            return Command::SUCCESS;
        }
        
        $this->info("Found {$lowStockItems->count()} items with low stock levels.");
        
        $bar = $this->output->createProgressBar($stakeholders->count());
        $bar->start();
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($stakeholders as $stakeholder) {
            try {
                $stakeholder->notify(new InventoryAlertNotification($lowStockItems));
                $successCount++;
                $bar->advance();
            } catch (\Exception $e) {
                $this->error("Failed to send inventory alert to {$stakeholder->name}: " . $e->getMessage());
                $errorCount++;
                $bar->advance();
            }
        }
        
        $bar->finish();
        $this->newLine();
        
        if ($successCount > 0) {
            $this->info("✅ Successfully sent {$successCount} inventory alerts!");
        }
        
        if ($errorCount > 0) {
            $this->warn("⚠️  Failed to send {$errorCount} alerts. Check logs for details.");
        }
        
        // Display low stock items summary
        $this->newLine();
        $this->info("Low Stock Items Summary:");
        $this->table(
            ['Product', 'Current Stock', 'Threshold'],
            $lowStockItems->take(10)->map(function ($item) use ($threshold) {
                return [
                    $item->product->name ?? 'Unknown Product',
                    $item->quantity,
                    $threshold
                ];
            })
        );
        
        return Command::SUCCESS;
    }
} 