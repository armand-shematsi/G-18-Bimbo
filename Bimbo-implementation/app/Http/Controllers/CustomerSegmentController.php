<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    // 1. Upload dataset
    public function uploadDataset(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);
        $file = $request->file('csv_file');
        $file->move(base_path('ml'), 'customer_purchase_data.csv');
        return back()->with('success', 'Dataset uploaded successfully!');
    }

    // 2. Run segmentation script
    public function runSegmentation()
    {
        $scriptPath = base_path('ml/customer_segmentation.py');
        $csvPath = base_path('ml/customer_purchase_data.csv');
        if (!file_exists($csvPath)) {
            return back()->with('error', 'Dataset file not found. Please upload a dataset before running segmentation.');
        }
        $output = shell_exec('python ' . escapeshellarg($scriptPath) . ' 2>&1');
        if (strpos($output, 'Segmentation complete') !== false) {
            return back()->with('success', 'Segmentation script ran successfully!');
        } else {
            return back()->with('error', 'Segmentation failed: ' . $output);
        }
    }

    // 3. Import segments into DB
    public function importSegments()
    {
        $csvPath = base_path('ml/customer_segments_detailed.csv');
        if (!file_exists($csvPath)) {
            return back()->with('error', 'Segment file not found!');
        }
        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file);
        $required = ['Customer_ID', 'Purchase_Frequency', 'Avg_Order_Value', 'Location', 'Bread_Type', 'segment'];
        // Check for required headers
        foreach ($required as $col) {
            if (!in_array($col, $header)) {
                fclose($file);
                return back()->with('error', "CSV header missing required column: $col. Please check your file.");
            }
        }
        $count = 0;
        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);
            // Skip rows missing required fields
            $skip = false;
            foreach ($required as $col) {
                if (!isset($data[$col]) || $data[$col] === '') {
                    $skip = true;
                    break;
                }
            }
            if ($skip) continue;
            DB::table('customer_segments')->updateOrInsert(
                ['CustomerID' => $data['Customer_ID']],
                [
                    'CustomerID' => $data['Customer_ID'],
                    'Name' => 'Customer ' . $data['Customer_ID'],
                    'PurchaseFrequency' => (int)$data['Purchase_Frequency'],
                    'AvgSpending' => (float)$data['Avg_Order_Value'],
                    'Location' => $data['Location'],
                    'PreferredBreadType' => $data['Bread_Type'],
                    'Segment' => isset($data['segment']) ? (int)$data['segment'] : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            $count++;
        }
        fclose($file);
        return back()->with('success', "Imported {$count} customer segments!");
    }
}
