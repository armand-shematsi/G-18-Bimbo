<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ReportService;
use App\Models\User;

class TestReportingSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:test {--stakeholder=admin : Stakeholder to test} {--type=daily : Report type (daily, weekly, inventory)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the reporting system functionality';

    /**
     * Execute the console command.
     */
    public function handle(ReportService $reportService)
    {
        $stakeholder = $this->option('stakeholder');
        $type = $this->option('type');
        
        $this->info("🧪 Testing Reporting System");
        $this->info("Stakeholder: {$stakeholder}");
        $this->info("Report Type: {$type}");
        $this->newLine();
        
        // Test 1: Check if stakeholder exists
        $this->info("1. Checking stakeholder existence...");
        $user = User::where('role', $stakeholder)->first();
        
        if (!$user) {
            $this->error("❌ No user found with role: {$stakeholder}");
            $this->warn("Creating a test user for demonstration...");
            $user = User::create([
                'name' => 'Test ' . ucfirst($stakeholder),
                'email' => "test.{$stakeholder}@bimbo.com",
                'role' => $stakeholder,
                'status' => 'active',
            ]);
            $this->info("✅ Test user created: {$user->name}");
        } else {
            $this->info("✅ Found user: {$user->name}");
        }
        
        // Test 2: Generate report data
        $this->info("2. Generating report data...");
        try {
            switch ($type) {
                case 'daily':
                    $reportData = $reportService->generateDailyReport($stakeholder);
                    break;
                case 'weekly':
                    $reportData = $reportService->generateWeeklyReport($stakeholder);
                    break;
                case 'inventory':
                    $reportData = $reportService->getLowStockItems(10);
                    $this->info("✅ Found {$reportData->count()} low stock items");
                    return Command::SUCCESS;
                default:
                    $this->error("❌ Invalid report type: {$type}");
                    return Command::FAILURE;
            }
            
            $this->info("✅ Report data generated successfully");
            $this->info("   Report type: " . ($reportData['report_type'] ?? 'N/A'));
            $this->info("   Date/Period: " . ($reportData['date'] ?? $reportData['period'] ?? 'N/A'));
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to generate report data: " . $e->getMessage());
            return Command::FAILURE;
        }
        
        // Test 3: Test PDF generation
        $this->info("3. Testing PDF generation...");
        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("reports.{$type}", [
                'reportData' => $reportData,
                'user' => $user,
                'roleName' => ucfirst($stakeholder),
                'date' => $reportData['date'] ?? now()->format('Y-m-d'),
                'period' => $reportData['period'] ?? 'Test Period',
            ]);
            
            $filename = "test_report_{$stakeholder}_{$type}_" . now()->format('Y-m-d_H-i-s') . ".pdf";
            $path = "reports/test/{$filename}";
            
            \Storage::put($path, $pdf->output());
            
            $this->info("✅ PDF generated successfully");
            $this->info("   File: {$path}");
            $this->info("   Size: " . number_format(\Storage::size($path)) . " bytes");
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to generate PDF: " . $e->getMessage());
            return Command::FAILURE;
        }
        
        // Test 4: Test email notification
        $this->info("4. Testing email notification...");
        try {
            switch ($type) {
                case 'daily':
                    $notification = new \App\Notifications\DailyReportNotification($reportData);
                    break;
                case 'weekly':
                    $notification = new \App\Notifications\WeeklyReportNotification($reportData);
                    break;
                default:
                    $this->error("❌ Invalid notification type for {$type}");
                    return Command::FAILURE;
            }
            
            $this->info("✅ Notification created successfully");
            $this->info("   Notification class: " . get_class($notification));
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to create notification: " . $e->getMessage());
            return Command::FAILURE;
        }
        
        // Test 5: Display report summary
        $this->newLine();
        $this->info("5. Report Summary:");
        if (isset($reportData['summary'])) {
            $this->table(
                ['Metric', 'Value'],
                collect($reportData['summary'])->map(function ($value, $key) {
                    return [
                        ucwords(str_replace('_', ' ', $key)),
                        is_numeric($value) ? number_format($value) : $value
                    ];
                })
            );
        }
        
        $this->newLine();
        $this->info("🎉 All tests passed! The reporting system is working correctly.");
        $this->info("📧 To send actual emails, run: php artisan reports:{$type} --stakeholder={$stakeholder}");
        
        return Command::SUCCESS;
    }
} 