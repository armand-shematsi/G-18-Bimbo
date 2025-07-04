<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Segment distribution
        $segmentCounts = \DB::table('customer_segments')
            ->select('Segment', \DB::raw('count(*) as count'))
            ->groupBy('Segment')
            ->orderBy('Segment')
            ->get();

        // Average spending per segment
        $avgSpending = \DB::table('customer_segments')
            ->select('Segment', \DB::raw('avg(AvgSpending) as avg_spending'))
            ->groupBy('Segment')
            ->orderBy('Segment')
            ->get();

        return view('admin.analytics.index', compact('segmentCounts', 'avgSpending'));
    }
}
