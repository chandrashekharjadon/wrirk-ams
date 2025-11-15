<?php

// namespace App\Http\Controllers\Employee;

// use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Auth;
// use App\Models\FinalAttendance;

// class AttendanceController extends Controller
// {
//     public function index()
//     {
//         $user = Auth::user();
//         $month = request('month');
//         $year = request('year');

//         // Dynamically generate a 5-year range (2 years back, 2 ahead)
//         $years = range(now()->year - 2, now()->year + 2);

//         $attendances = collect(); // empty by default

//         if ($month && $year) {
//             $attendances = FinalAttendance::where('user_id', $user->id)
//                 ->whereMonth('date', $month)
//                 ->whereYear('date', $year)
//                 ->orderBy('date', 'asc')
//                 ->get();
//         }

//         return view('employee.attendance', compact('attendances', 'month', 'year', 'years'));
//     }
// }



namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\AttendanceReportService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    protected $reportService;

    public function __construct(AttendanceReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);
        $years = range(now()->year - 2, now()->year + 2);

        $weeks = [];
        $month_summary = [];

        if ($request->filled(['month', 'year'])) {
            $results = $this->reportService->getWeeklyReport($user->id, $month, $year);
            $weeks = $results['weeks'] ?? [];
            $month_summary = $results['month_summary'] ?? [];
        }

        return view('employee.attendance', compact(
        'weeks', 'month_summary', 'month', 'year', 'years'
         ));

    }
}