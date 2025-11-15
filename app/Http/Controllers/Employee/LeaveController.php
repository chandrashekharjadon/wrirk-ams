<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Leave;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get selected month (default = current month)
        $month = $request->input('month', now()->format('Y-m'));

        // Fetch leaves for that month
        $leaves = Leave::where('user_id', $user->id)
                        ->whereMonth('date', '=', date('m', strtotime($month)))
                        ->whereYear('date', '=', date('Y', strtotime($month)))
                        ->orderBy('date', 'desc')
                        ->get();

        return view('employee.leaves', compact('leaves', 'month'));
    }
}
