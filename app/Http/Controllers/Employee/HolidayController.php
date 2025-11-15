<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use Carbon\Carbon;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        // Get filter values
        $month = $request->input('month');
        $year  = $request->input('year');

        $query = Holiday::query();

        // Apply month filter if selected
        if ($month) {
            $query->whereMonth('date', '=', $month);
        }

        // Apply year filter if selected
        if ($year) {
            $query->whereYear('date', '=', $year);
        }

        // Default: show only upcoming holidays if no filter
        if (!$month && !$year) {
            $query->where('date', '>=', now());
        }

        // Fetch holidays
        $holidays = $query->orderBy('date', 'asc')->get();

        return view('employee.holidays', compact('holidays', 'month', 'year'));
    }
}
