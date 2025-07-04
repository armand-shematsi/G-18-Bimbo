<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\CustomerSegment;

class CustomerSegmentImportController extends Controller
{
    // Show upload form (optional, for web UI)
    public function showForm()
    {
        return view('customer_segments.import');
    }

    // Handle CSV upload and import
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $header = array_map('trim', $data[0]);
        if (!in_array('Segment', $header)) {
            return back()->withErrors(['csv_file' => 'The uploaded CSV is missing the required "Segment" column. Please check your file and try again.']);
        }
        unset($data[0]);

        foreach ($data as $row) {
            $row = array_combine($header, $row);
            if (!$row['CustomerID']) continue;
            // Insert or update by CustomerID
            \DB::table('customer_segments')->updateOrInsert(
                ['CustomerID' => $row['CustomerID']],
                [
                    'Name' => $row['Name'],
                    'PurchaseFrequency' => isset($row['PurchaseFrequency']) ? (int)$row['PurchaseFrequency'] : null,
                    'AvgSpending' => isset($row['AvgSpending']) ? (int)$row['AvgSpending'] : null,
                    'Location' => $row['Location'] ?? null,
                    'PreferredBreadType' => $row['PreferredBreadType'] ?? null,
                    'Segment' => isset($row['Segment']) ? (int)$row['Segment'] : null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        return back()->with('success', 'Customer segments imported successfully!');
    }
}
