<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AttendanceReportService;
use Illuminate\Http\Request;
use Carbon\Carbon;


class AttendanceReportController extends Controller
{
    protected $reportService;

    public function __construct(AttendanceReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $users = User::all();
        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);

        // Default empty data
        $weeks = [];
        $month_summary = [];

        // Run report only if filters are filled
        if ($request->filled(['user_id', 'month', 'year'])) {
            $results = $this->reportService->getWeeklyReport($request->user_id, $month, $year);

            $weeks = $results['weeks'] ?? [];
            $month_summary = $results['month_summary'] ?? [];
            // dd($month_summary);
            // dd($weeks);
        }

        return view('Admin.attendance.index', compact('users', 'weeks', 'month_summary', 'month', 'year'));
    }

}
