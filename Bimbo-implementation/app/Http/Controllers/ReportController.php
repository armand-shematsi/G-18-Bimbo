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

        // Update these paths to match your storage location
        $dailyPath = "sentreports/dailyreports";
        $weeklyPath = "sentreports/weeklyreports";

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
        $path = "private/reports/{$type}/{$filename}";
        if (!Storage::exists($path)) {
            abort(404);
        }
        return Storage::download($path);
    }
}
