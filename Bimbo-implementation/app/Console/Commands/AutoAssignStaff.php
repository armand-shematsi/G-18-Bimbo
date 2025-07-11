<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StaffAssignmentService;

class AutoAssignStaff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workforce:auto-assign {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically assign staff to supply centers for the day';

    /**
     * Execute the console command.
     */
    public function handle(StaffAssignmentService $service)
    {
        $date = $this->option('date') ?? now()->toDateString();
        $result = $service->autoAssignStaff($date);
        if ($result['success']) {
            $this->info('Staff auto-assigned successfully for ' . $date);
        } else {
            $this->error('Auto-assignment failed: ' . ($result['message'] ?? 'Unknown error'));
        }
    }
}
