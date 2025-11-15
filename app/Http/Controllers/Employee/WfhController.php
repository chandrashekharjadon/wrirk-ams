<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Wfh;

class WfhController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get selected month (default = current month)
        $month = $request->input('month', now()->format('Y-m'));
        $wfhs = Wfh::where('user_id', $user->id)
                        ->whereMonth('date', '=', date('m', strtotime($month)))
                        ->whereYear('date', '=', date('Y', strtotime($month)))
                        ->orderBy('date', 'desc')
                        ->get();

        return view('employee.wfh', compact('wfhs', 'month'));
                
    }
    // public function index(Request $request)
    // {
    //     $user = Auth::user();

    //     // Base query for the employee’s WFH
    //     $query = Wfh::where('user_id', $user->id);

    //     // Apply single date filter (if selected)
    //     if ($request->filled('date')) {
    //         $query->whereDate('date', $request->date);
    //     }

    //     $wfhs = $query->orderBy('date', 'desc')->get();

    //     return view('employee.wfh', compact('wfhs'))
    //             ->with(['date' => $request->date]);
    // }
}
