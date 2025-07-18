<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ReportDownloadController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $dailyFiles = Storage::disk('private')->files('sentreports/dailyreports');
        $userDailyFiles = array_filter($dailyFiles, fn($file) => str_contains($file, "daily_report_{$user->id}_"));
        $weeklyFiles = Storage::disk('private')->files('sentreports/weeklyreports');
        $userWeeklyFiles = array_filter($weeklyFiles, fn($file) => str_contains($file, "weekly_report_{$user->id}_"));
        return view('reports.downloads', [
            'dailyFiles' => $userDailyFiles,
            'weeklyFiles' => $userWeeklyFiles,
        ]);
    }

    public function download($filename)
    {
        $path = "sentreports/dailyreports/{$filename}";
        if (!Storage::disk('private')->exists($path)) abort(404);
        return Storage::disk('private')->download($path);
    }

    public function weeklyDownload($filename)
    {
        $path = "sentreports/weeklyreports/{$filename}";
        if (!Storage::disk('private')->exists($path)) abort(404);
        return Storage::disk('private')->download($path);
    }

    public function view($filename)
    {
        $path = "sentreports/dailyreports/{$filename}";
        if (!Storage::disk('private')->exists($path)) {
            \Log::error('File not found for view: ' . $path);
            \Log::error('Files in sentreports/dailyreports: ' . json_encode(Storage::disk('private')->files('sentreports/dailyreports')));
            abort(404);
        }
        return response()->file(Storage::disk('private')->path($path));
    }

    public function weeklyView($filename)
    {
        $path = "sentreports/weeklyreports/{$filename}";
        if (!Storage::disk('private')->exists($path)) abort(404);
        return response()->file(Storage::disk('private')->path($path));
    }

    public function generate(Request $request)
    {
        $user = $request->user();
        $role = $user->role;
        $userId = $user->id;
        $reportService = app(\App\Services\ReportService::class);
        $statusMessages = [];
        try {
            // Ensure directory exists
            Storage::disk('private')->makeDirectory('sentreports/dailyreports');
            // Generate daily report data
            $dailyData = $reportService->generateDailyReport($role, $userId);
            $date = $dailyData['date'] ?? now()->format('Y-m-d');
            $roleView = match ($dailyData['report_type'] ?? '') {
                'bakery_manager_daily' => 'reports.bakery_manager',
                'retail_manager_daily' => 'reports.retail_manager',
                'distributor_daily' => 'reports.distributor',
                'supplier_daily' => 'reports.supplier',
                'customer_daily' => 'reports.customer',
                'admin_daily' => 'reports.admin',
                default => 'reports.daily',
            };
            $dailyPdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($roleView, [
                'reportData' => $dailyData,
                'user' => $user,
                'roleName' => ucfirst($role),
                'date' => $date,
            ]);
            $dailyFilename = "daily_report_{$userId}_{$date}.pdf";
            $dailyPath = "sentreports/dailyreports/{$dailyFilename}";
            Storage::disk('private')->put($dailyPath, $dailyPdf->output());
            $statusMessages[] = 'Daily report generated.';
        } catch (\Exception $e) {
            $statusMessages[] = 'Failed to generate daily report: ' . $e->getMessage();
        }
        try {
            // Ensure directory exists
            Storage::disk('private')->makeDirectory('sentreports/weeklyreports');
            // Generate weekly report data
            $weeklyData = $reportService->generateWeeklyReport($role, $userId);
            $period = $weeklyData['period'] ?? now()->startOfWeek()->format('Y-m-d') . '_to_' . now()->endOfWeek()->format('Y-m-d');
            $roleView = match ($weeklyData['report_type'] ?? '') {
                'bakery_manager_weekly' => 'reports.bakery_manager',
                'retail_manager_weekly' => 'reports.retail_manager',
                'distributor_weekly' => 'reports.distributor',
                'supplier_weekly' => 'reports.supplier',
                'customer_weekly' => 'reports.customer',
                'admin_weekly' => 'reports.admin',
                default => 'reports.weekly',
            };
            $weeklyPdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($roleView, [
                'reportData' => $weeklyData,
                'user' => $user,
                'roleName' => ucfirst($role),
                'period' => $period,
            ]);
            $weeklyFilename = "weekly_report_{$userId}_" . now()->format('Y-m-d') . ".pdf";
            $weeklyPath = "sentreports/weeklyreports/{$weeklyFilename}";
            Storage::disk('private')->put($weeklyPath, $weeklyPdf->output());
            $statusMessages[] = 'Weekly report generated.';
        } catch (\Exception $e) {
            $statusMessages[] = 'Failed to generate weekly report: ' . $e->getMessage();
        }
        return redirect()->back()->with('status', implode(' ', $statusMessages));
    }
} 