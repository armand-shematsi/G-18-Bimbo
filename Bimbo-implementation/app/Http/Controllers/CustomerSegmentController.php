<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerSegmentController extends Controller
{
    public function index()
    {
        $segments = DB::table('customer_segments')->paginate(20);

        // Get analytics data for charts
        $analyticsData = $this->getAnalyticsData();

        return view('customer_segments.index', compact('segments', 'analyticsData'));
    }

    private function getAnalyticsData()
    {
        // Get segment distribution
        $segmentDistribution = DB::table('customer_segments')
            ->select('Segment', DB::raw('count(*) as count'))
            ->groupBy('Segment')
            ->get();

        // Get average spending by segment
        $avgSpendingBySegment = DB::table('customer_segments')
            ->select('Segment', DB::raw('AVG(AvgSpending) as avg_spending'))
            ->groupBy('Segment')
            ->get();

        // Get purchase frequency by segment
        $frequencyBySegment = DB::table('customer_segments')
            ->select('Segment', DB::raw('AVG(PurchaseFrequency) as avg_frequency'))
            ->groupBy('Segment')
            ->get();

        // Get location distribution
        $locationDistribution = DB::table('customer_segments')
            ->select('Location', DB::raw('count(*) as count'))
            ->groupBy('Location')
            ->get();

        // Get bread type preferences
        $breadPreferences = DB::table('customer_segments')
            ->select('PreferredBreadType', DB::raw('count(*) as count'))
            ->groupBy('PreferredBreadType')
            ->get();

        $data = [
            'segmentDistribution' => $segmentDistribution,
            'avgSpendingBySegment' => $avgSpendingBySegment,
            'frequencyBySegment' => $frequencyBySegment,
            'locationDistribution' => $locationDistribution,
            'breadPreferences' => $breadPreferences,
            'totalCustomers' => DB::table('customer_segments')->count(),
            'totalSegments' => DB::table('customer_segments')->distinct('Segment')->count(),
            'avgSpending' => DB::table('customer_segments')->avg('AvgSpending'),
            'avgFrequency' => DB::table('customer_segments')->avg('PurchaseFrequency'),
        ];

        return $data;
    }

    public function getChartData()
    {
        $analyticsData = $this->getAnalyticsData();

        return response()->json([
            'segmentDistribution' => [
                'labels' => $analyticsData['segmentDistribution']->pluck('Segment'),
                'data' => $analyticsData['segmentDistribution']->pluck('count'),
            ],
            'avgSpendingBySegment' => [
                'labels' => $analyticsData['avgSpendingBySegment']->pluck('Segment'),
                'data' => $analyticsData['avgSpendingBySegment']->pluck('avg_spending'),
            ],
            'frequencyBySegment' => [
                'labels' => $analyticsData['frequencyBySegment']->pluck('Segment'),
                'data' => $analyticsData['frequencyBySegment']->pluck('avg_frequency'),
            ],
            'locationDistribution' => [
                'labels' => $analyticsData['locationDistribution']->pluck('Location'),
                'data' => $analyticsData['locationDistribution']->pluck('count'),
            ],
            'breadPreferences' => [
                'labels' => $analyticsData['breadPreferences']->pluck('PreferredBreadType'),
                'data' => $analyticsData['breadPreferences']->pluck('count'),
            ],
        ]);
    }
}
