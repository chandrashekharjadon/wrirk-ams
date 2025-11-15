<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Wfh;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\UserSalary;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Sample summary counts (adjust if you have specific logic)
        $wfhCount = Wfh::where('user_id', $user->id)->count();
        $attendanceCount = 20; // Example, replace with actual attendance data
        $leaveBalance = optional($user->cl)->remaining ?? 0;
        $upcomingHolidays = Holiday::where('date', '>=', now())->count();
        $lastSalary = optional(UserSalary::where('user_id', $user->id)->latest()->first())->net_salary ?? 0;

        // Recent WFHs
        $recentWfhs = Wfh::where('user_id', $user->id)->latest()->take(5)->get();

        return view('employee.dashboard', compact(
            'user',
            'wfhCount',
            'attendanceCount',
            'leaveBalance',
            'upcomingHolidays',
            'lastSalary',
            'recentWfhs'
        ));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('employee.profile', compact('user'));
    }  
}
