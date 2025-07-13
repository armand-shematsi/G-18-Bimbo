<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ReportDownloadController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $dailyFiles = \Storage::files('sentreports/dailyreports');
        $userDailyFiles = array_filter($dailyFiles, fn($file) => str_contains($file, "daily_report_{$user->id}_"));
        $weeklyFiles = \Storage::files('sentreports/weeklyreports');
        $userWeeklyFiles = array_filter($weeklyFiles, fn($file) => str_contains($file, "weekly_report_{$user->id}_"));
        return view('reports.downloads', [
            'dailyFiles' => $userDailyFiles,
            'weeklyFiles' => $userWeeklyFiles,
        ]);
    }

    public function download($filename)
    {
        $path = "sentreports/dailyreports/{$filename}";
        if (!Storage::exists($path)) abort(404);
        return Storage::download($path);
    }

    public function weeklyDownload($filename)
    {
        $path = "sentreports/weeklyreports/{$filename}";
        if (!\Storage::exists($path)) abort(404);
        return \Storage::download($path);
    }

    public function view($filename)
    {
        $path = "sentreports/dailyreports/{$filename}";
        if (!\Storage::exists($path)) abort(404);
        return response()->file(\Storage::path($path));
    }

    public function weeklyView($filename)
    {
        $path = "sentreports/weeklyreports/{$filename}";
        if (!\Storage::exists($path)) abort(404);
        return response()->file(\Storage::path($path));
    }
} 