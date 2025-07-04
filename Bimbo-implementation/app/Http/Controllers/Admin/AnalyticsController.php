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

        // Top 5 customers by spending
        $topCustomers = \DB::table('customer_segments')
            ->orderByDesc('AvgSpending')
            ->take(5)
            ->get(['Name', 'AvgSpending']);

        // Preferred bread type distribution
        $breadTypeDistribution = \DB::table('customer_segments')
            ->select('PreferredBreadType', \DB::raw('count(*) as count'))
            ->groupBy('PreferredBreadType')
            ->orderByDesc('count')
            ->get();

        // Customer location distribution
        $locationDistribution = \DB::table('customer_segments')
            ->select('Location', \DB::raw('count(*) as count'))
            ->groupBy('Location')
            ->orderByDesc('count')
            ->get();

        // Average purchase frequency by segment
        $avgPurchaseFrequency = \DB::table('customer_segments')
            ->select('Segment', \DB::raw('avg(PurchaseFrequency) as avg_frequency'))
            ->groupBy('Segment')
            ->orderBy('Segment')
            ->get();

        return view('admin.analytics.index', compact(
            'segmentCounts', 'avgSpending',
            'topCustomers', 'breadTypeDistribution',
            'locationDistribution', 'avgPurchaseFrequency'
        ));
    }
}
