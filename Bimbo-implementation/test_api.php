<?php
// Simple test script to check the API endpoint
require_once 'vendor/autoload.php';

use App\Models\Staff;

// Count staff
$presentCount = Staff::where('status', 'Present')->count();
$absentCount = Staff::where('status', 'Absent')->count();
$totalStaff = Staff::count();

echo "Present: $presentCount\n";
echo "Absent: $absentCount\n";
echo "Total: $totalStaff\n";

// Show all staff
$allStaff = Staff::all();
foreach ($allStaff as $staff) {
    echo "ID: {$staff->id}, Name: {$staff->name}, Role: {$staff->role}, Status: {$staff->status}\n";
}
