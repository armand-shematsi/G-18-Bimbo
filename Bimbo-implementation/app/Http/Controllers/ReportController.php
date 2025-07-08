<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;

        // You can adjust the path logic as needed for your report structure
        $dailyPath = "reports/daily";
        $weeklyPath = "reports/weekly";

        // Get all daily and weekly reports for this user (by user id in filename)
        $dailyReports = collect(Storage::files($dailyPath))
            ->filter(fn($file) => str_contains($file, "_{$user->id}_"))
            ->sortDesc();

        $weeklyReports = collect(Storage::files($weeklyPath))
            ->filter(fn($file) => str_contains($file, "_{$user->id}_"))
            ->sortDesc();

        return view('reports.index', compact('dailyReports', 'weeklyReports'));
    }

    public function download($type, $filename)
    {
        $path = "reports/{$type}/{$filename}";
        if (!Storage::exists($path)) {
            abort(404);
        }
        return Storage::download($path);
    }
}
