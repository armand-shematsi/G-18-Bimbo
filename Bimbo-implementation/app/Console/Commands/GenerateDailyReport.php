<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ReportService;
use App\Notifications\DailyReportNotification;
use App\Models\User;

class GenerateDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:daily {--stakeholder=all : Specific stakeholder type (admin, supplier, bakery_manager, distributor, retail_manager, customer)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and send daily reports to stakeholders';

    /**
     * Execute the console command.
     */
    public function handle(ReportService $reportService)
    {
        $stakeholderType = $this->option('stakeholder');
        
        $this->info('Generating daily reports...');
        
        // Get all active users (or all users if you want to include inactive)
        if ($stakeholderType === 'all') {
            $stakeholders = \App\Models\User::where('status', 'active')->get();
        } else {
            $stakeholders = \App\Models\User::where('role', $stakeholderType)->where('status', 'active')->get();
        }

        if ($stakeholders->isEmpty()) {
            $this->error("No users found for role: {$stakeholderType}");
            return Command::FAILURE;
        }
        
        $bar = $this->output->createProgressBar($stakeholders->count());
        $bar->start();
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($stakeholders as $stakeholder) {
            try {
                // Always generate a report, even if all values are zero
                $reportData = $reportService->generateDailyReport($stakeholder->role, $stakeholder->id);
                $stakeholder->notify(new \App\Notifications\DailyReportNotification($reportData));
                $successCount++;
                $bar->advance();
            } catch (\Exception $e) {
                $this->error("Failed to send report to {$stakeholder->name}: " . $e->getMessage());
                $errorCount++;
                $bar->advance();
            }
        }
        
        $bar->finish();
        $this->newLine();
        
        if ($successCount > 0) {
            $this->info("✅ Successfully sent {$successCount} daily reports!");
        }
        
        if ($errorCount > 0) {
            $this->warn("⚠️  Failed to send {$errorCount} reports. Check logs for details.");
        }
        
        return Command::SUCCESS;
    }
} 