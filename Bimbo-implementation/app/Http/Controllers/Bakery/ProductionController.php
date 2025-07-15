<?php

namespace App\Http\Controllers\Bakery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\ProductionBatch;
use App\Models\ProductionLine;
use App\Models\User;

class ProductionController extends Controller
{
    public function start()
    {
        $ingredients = \App\Models\Ingredient::orderBy('name')->get();
        $productionLines = \App\Models\ProductionLine::all();
        $staff = \App\Models\User::where('role', 'staff')->orderBy('name')->get();
        return view('bakery.production.start', compact('ingredients', 'productionLines', 'staff'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'scheduled_start' => 'required|date',
            'status' => 'required|in:planned,active,completed,cancelled',
            'quantity' => 'required|integer|min:1',
            'production_line_id' => 'required|exists:production_lines,id',
            'ingredients' => 'required|array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
        ]);

        $batch = ProductionBatch::create([
            'name' => $validated['name'],
            'scheduled_start' => $validated['scheduled_start'],
            'status' => $validated['status'],
            'quantity' => $validated['quantity'],
            'production_line_id' => $validated['production_line_id'],
        ]);

        // Attach ingredients with quantities
        $ingredientData = [];
        foreach ($validated['ingredients'] as $ingredient) {
            $ingredientData[$ingredient['id']] = ['quantity_used' => $ingredient['quantity']];
        }
        $batch->ingredients()->attach($ingredientData);

        // Assign staff as shifts (default: scheduled_start to scheduled_start+8h)
        if ($request->has('staff')) {
            $start = \Carbon\Carbon::parse($batch->scheduled_start);
            $end = $start->copy()->addHours(8);
            foreach ($request->input('staff') as $userId) {
                \App\Models\Shift::create([
                    'user_id' => $userId,
                    'production_batch_id' => $batch->id,
                    'start_time' => $start,
                    'end_time' => $end,
                    'role' => 'staff',
                ]);
            }
        }

        return redirect()->route('bakery.batches.index')->with('success', 'Production batch created successfully.');
    }

    // Add this method for production trends API
    public function trends()
    {
        $trends = \App\Models\ProductionBatch::selectRaw('DATE(actual_end) as date, SUM(quantity) as total_output')
            ->whereNotNull('actual_end')
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($trends);
    }

    // API endpoint for batch output per day for the last N days (for production trends chart)
    public function batchOutputTrends(Request $request)
    {
        $days = $request->query('days', 7); // default to 7, allow 30
        $startDate = now()->subDays($days - 1)->startOfDay();
        $batches = \App\Models\ProductionBatch::whereNotNull('actual_end')
            ->where('status', 'completed')
            ->where('actual_end', '>=', $startDate)
            ->orderBy('actual_end', 'asc')
            ->get(['quantity', 'actual_end']);
        $grouped = [];
        foreach ($batches as $batch) {
            $date = \Carbon\Carbon::parse($batch->actual_end)->toDateString();
            if (!isset($grouped[$date])) $grouped[$date] = 0;
            $grouped[$date] += $batch->quantity;
        }
        $result = [];
        for ($i = 0; $i < $days; $i++) {
            $date = now()->subDays($days - 1 - $i)->toDateString();
            $result[] = [
                'date' => $date,
                'total_output' => $grouped[$date] ?? 0,
            ];
        }
        return response()->json($result);
    }

    // API endpoint for batch output per day for a given week in a month (for production trends chart)
    public function batchOutputTrendsWeek(Request $request)
    {
        $month = $request->query('month'); // format: YYYY-MM
        $week = (int) $request->query('week');
        if (!$month || !$week) {
            return response()->json([], 400);
        }
        $year = (int)substr($month, 0, 4);
        $mon = (int)substr($month, 5, 2);
        $firstDay = \Carbon\Carbon::create($year, $mon, 1)->startOfDay();
        // Find the start of the requested week
        $start = $firstDay->copy();
        $weekNum = 1;
        while ($weekNum < $week && $start->month == $mon) {
            $end = $start->copy()->addDays(6 - $start->dayOfWeek);
            $start = $end->copy()->addDay();
            $weekNum++;
        }
        $end = $start->copy()->addDays(6 - $start->dayOfWeek);
        if ($end->month != $mon) $end = $start->copy()->endOfMonth();
        // Build all days in the week
        $days = [];
        $cur = $start->copy();
        while ($cur <= $end) {
            $days[] = $cur->toDateString();
            $cur->addDay();
        }
        $batches = \App\Models\ProductionBatch::whereNotNull('actual_end')
            ->where('status', 'completed')
            ->whereBetween('actual_end', [$start->toDateString().' 00:00:00', $end->toDateString().' 23:59:59'])
            ->get(['quantity', 'actual_end']);
        $grouped = [];
        foreach ($batches as $batch) {
            $date = \Carbon\Carbon::parse($batch->actual_end)->toDateString();
            if (!isset($grouped[$date])) $grouped[$date] = 0;
            $grouped[$date] += $batch->quantity;
        }
        $result = [];
        foreach ($days as $date) {
            $result[] = [
                'date' => $date,
                'total_output' => $grouped[$date] ?? 0,
            ];
        }
        return response()->json($result);
    }
}
