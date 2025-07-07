<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanupReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:cleanup {--days=30 : Number of days to keep reports}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old report files from storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $daysToKeep = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($daysToKeep);
        
        $this->info("Cleaning up report files older than {$daysToKeep} days...");
        
        $deletedCount = 0;
        $errorCount = 0;
        
        // Clean up daily reports
        $deletedCount += $this->cleanupDirectory('reports/daily', $cutoffDate);
        
        // Clean up weekly reports
        $deletedCount += $this->cleanupDirectory('reports/weekly', $cutoffDate);
        
        // Clean up alert reports
        $deletedCount += $this->cleanupDirectory('reports/alerts', $cutoffDate);
        
        $this->info("✅ Cleanup completed! Deleted {$deletedCount} old report files.");
        
        if ($errorCount > 0) {
            $this->warn("⚠️  {$errorCount} files could not be deleted. Check logs for details.");
        }
        
        return Command::SUCCESS;
    }
    
    /**
     * Clean up files in a specific directory
     */
    private function cleanupDirectory(string $directory, Carbon $cutoffDate): int
    {
        if (!Storage::exists($directory)) {
            return 0;
        }
        
        $files = Storage::files($directory);
        $deletedCount = 0;
        
        foreach ($files as $file) {
            try {
                $fileTime = Storage::lastModified($file);
                $fileDate = Carbon::createFromTimestamp($fileTime);
                
                if ($fileDate->lt($cutoffDate)) {
                    Storage::delete($file);
                    $deletedCount++;
                }
            } catch (\Exception $e) {
                $this->error("Failed to process file {$file}: " . $e->getMessage());
            }
        }
        
        return $deletedCount;
    }
} 