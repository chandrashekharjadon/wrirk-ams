<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\MonthRecord;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class SalarySlipController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Filter month/year from request or default to current
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Query for MonthRecord of logged-in user
        $monthRecord = MonthRecord::where('user_id', $user->id)
                        ->where('month', $month)
                        ->where('year', $year)
                        ->whereIn('status', ['approved', 'update and approved'])
                        ->first();

        return view('employee.salary-slip', [
            'monthRecord' => $monthRecord,
        ]);
    }

    public function pdfData(MonthRecord $monthRecord)
    {
        // Load your PDF Blade view
        $pdf = PDF::loadView('employee.salary-slip-pdf', [
            'monthRecord' => $monthRecord
        ])
        ->setPaper('a4')
        ->setOption('margin-top', 10)
        ->setOption('margin-bottom', 10)
        ->setOption('encoding', 'UTF-8')
        ->setOption('enable-local-file-access', true);

        // Return inline view or download
        return $pdf->inline('SalarySlip-'.$monthRecord->user->name.'-'.$monthRecord->month.'.pdf');
    }
}
