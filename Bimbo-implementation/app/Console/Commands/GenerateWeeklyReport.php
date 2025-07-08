<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ReportService;
use App\Notifications\WeeklyReportNotification;
use App\Models\User;

class GenerateWeeklyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:weekly {--stakeholder=all : Specific stakeholder type (admin, supplier, bakery_manager, distributor, retail_manager, customer)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and send weekly reports to stakeholders';

    /**
     * Execute the console command.
     */
    public function handle(ReportService $reportService)
    {
        $stakeholderType = $this->option('stakeholder');
        
        $this->info('Generating weekly reports...');
        
        if ($stakeholderType === 'all') {
            $stakeholders = User::whereIn('role', ['admin', 'supplier', 'bakery_manager', 'distributor', 'retail_manager', 'customer'])
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
        
        $bar = $this->output->createProgressBar($stakeholders->count());
        $bar->start();
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($stakeholders as $stakeholder) {
            try {
                $reportData = $reportService->generateWeeklyReport($stakeholder->role);
                $stakeholder->notify(new WeeklyReportNotification($reportData));
                $successCount++;
                $bar->advance();
            } catch (\Exception $e) {
                $this->error("Failed to send weekly report to {$stakeholder->name}: " . $e->getMessage());
                $errorCount++;
                $bar->advance();
            }
        }
        
        $bar->finish();
        $this->newLine();
        
        if ($successCount > 0) {
            $this->info("✅ Successfully sent {$successCount} weekly reports!");
        }
        
        if ($errorCount > 0) {
            $this->warn("⚠️  Failed to send {$errorCount} reports. Check logs for details.");
        }
        
        return Command::SUCCESS;
    }
} 