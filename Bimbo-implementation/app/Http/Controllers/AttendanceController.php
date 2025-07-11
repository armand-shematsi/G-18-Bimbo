<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'date' => 'required|date',
                'status' => 'required|in:present,absent',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => 'Validation failed'], 422);
            }

            $attendance = Attendance::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'date' => $request->date,
                ],
                [
                    'status' => $request->status,
                ]
            );

            return response()->json(['success' => true, 'attendance' => $attendance]);
        } catch (\Exception $e) {
            Log::error('Attendance update error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
