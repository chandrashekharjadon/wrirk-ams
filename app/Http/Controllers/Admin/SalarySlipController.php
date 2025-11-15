<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AttendanceReportService;
use App\Services\WfhDateDataService;
use App\Services\SalaryCalculationService;
use App\Models\User;
use App\Models\MonthRecord;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;


class SalarySlipController extends Controller
{
    protected $reportService;
    // protected $wfhService;
    protected $salCalService;

    public function __construct(AttendanceReportService $reportService, SalaryCalculationService $salCalService, )
    {
        $this->reportService = $reportService;
        $this->salCalService = $salCalService;
    }

    public function index(Request $request)
    {
        // Fetch all users for filter dropdown
        $users = User::all();

        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);
        $userId = $request->input('user_id');

        $monthRecord = null;

        // Only run if filters are selected
        if ($request->filled(['user_id', 'month', 'year'])) {
            // Step 1️⃣: Get attendance summary
            $results = $this->reportService->getWeeklyReport($userId, $month, $year);

            // Step 2️⃣: Find or create month record
            $monthRecord = MonthRecord::where('user_id', $userId)
                ->where('month', $month)
                ->where('year', $year)
                ->first();

            // Step 3️⃣: If not exists, create it
            if (!$monthRecord) {
                $calculatedData = $this->salCalService->calculateMonthlySalary(
                    $userId,
                    $month,
                    $year,
                    $results,
                );

                $monthRecord = MonthRecord::create([
                    'user_id'         => $userId,
                    'month'           => $month,
                    'year'            => $year,
                    'working_hours'   => $calculatedData['working_hours'] ?? 0,
                    'working_days'   => $calculatedData['working_days'] ?? 0,
                    'half_days'       => $calculatedData['half_days'] ?? 0,
                    'leaves'          => $calculatedData['leaves'] ?? 0,
                    'sandwitch'       => $calculatedData['sandwitch'] ?? 0,
                    'wfh'             => $calculatedData['wfh'] ?? 0,
                    'wfh_dates'       => $calculatedData['wfh_dates'] ?? null,
                    'wfh_percentages' => $calculatedData['wfh_percentages'] ?? [],
                    'wfh_prices'      => $calculatedData['wfh_prices'] ?? [],
                    'total_wfh_cost'  => $calculatedData['total_wfh_cost'] ?? 0,
                    'gross_salary'     => $calculatedData['gross_salary'] ?? 0,
                    'basic'            => $calculatedData['basic'] ?? 0,
                    'hra'              => $calculatedData['hra'] ?? 0,
                    'conveyance'       => $calculatedData['conveyance'] ?? 0,
                    'simple_allowance' => $calculatedData['simple_allowance'] ?? 0,
                    'other_allowance'  => $calculatedData['other_allowance'] ?? 0,
                    'used_cl'      => $calculatedData['used_cl'] ?? 0,
                    'available_cl' => $calculatedData['available_cl'] ?? 0,
                    'leave_deduction'     => $calculatedData['leave_deduction'] ?? 0,
                    'half_day_deduction'  => $calculatedData['half_day_deduction'] ?? 0,
                    'total_deduction'     => $calculatedData['total_deduction'] ?? 0,
                    'net_salary'          => $calculatedData['net_salary'] ?? 0,
                    'daily_rate'          => $calculatedData['daily_rate'] ?? 0,
                ]);
            }
        }

        return view('Admin.salaryslip.index', compact('users', 'month', 'year', 'monthRecord'));
    }

     /**
     * Show the edit form
     */
    public function edit(MonthRecord $monthRecord)
    {
        $users = User::all();
        return view('Admin.salaryslip.edit', compact('monthRecord', 'users'));
    }

    /**
     * Update the salary record
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'working_hours'       => 'nullable|numeric|min:0',
            'working_days'       => 'nullable|numeric|min:0',
            'half_days'       => 'nullable|numeric|min:0',
            'leaves'          => 'nullable|numeric|min:0',
            'sandwitch'       => 'nullable|numeric|min:0',
            'wfh'             => 'nullable|numeric|min:0',
            'wfh_dates'       => 'nullable|array',
            'wfh_percentages' => 'nullable|array',
        ]);

        $monthRecord = MonthRecord::findOrFail($id);

        $results =$request->only([
            'working_hours',
            'working_days',
            'half_days',
            'leaves',
            'sandwitch',
            'wfh',
            'wfh_dates',
            'wfh_percentages',
        ]);
        
        // dd($results);
        $calculatedData = $this->salCalService->calculateMonthlySalary(
                $monthRecord->user_id,
                $monthRecord->month,
                $monthRecord->year,
                $results,
                $monthRecord
        );
    
        $monthRecord->update([
            // 📅 Attendance & Leave
            'working_hours'   => $calculatedData['working_hours'] ?? 0,
            'working_days'   => $calculatedData['working_days'] ?? 0,
            'half_days'       => $calculatedData['half_days'] ?? 0,
            'leaves'          => $calculatedData['leaves'] ?? 0,
            'sandwitch'       => $calculatedData['sandwitch'] ?? 0,
            'wfh'             => $calculatedData['wfh'] ?? 0,

            // 💻 WFH details
            'wfh_dates'       => $calculatedData['wfh_dates'] ?? null,
            'wfh_percentages' => $calculatedData['wfh_percentages'] ?? [],
            'wfh_prices'      => $calculatedData['wfh_prices'] ?? [],
            'total_wfh_cost'  => $calculatedData['total_wfh_cost'] ?? 0,

            // 💰 Salary structure
            'gross_salary'     => $calculatedData['gross_salary'] ?? 0,
            'basic'            => $calculatedData['basic'] ?? 0,
            'hra'              => $calculatedData['hra'] ?? 0,
            'conveyance'       => $calculatedData['conveyance'] ?? 0,
            'simple_allowance' => $calculatedData['simple_allowance'] ?? 0,
            'other_allowance'  => $calculatedData['other_allowance'] ?? 0,

            // 🧾 CL info
            'used_cl'      => $calculatedData['used_cl'] ?? 0,
            'available_cl' => $calculatedData['available_cl'] ?? 0,

            // 💸 Deductions & net salary
            'leave_deduction'     => $calculatedData['leave_deduction'] ?? 0,
            'half_day_deduction'  => $calculatedData['half_day_deduction'] ?? 0,
            'total_deduction'     => $calculatedData['total_deduction'] ?? 0,
            'net_salary'          => $calculatedData['net_salary'] ?? 0,
            'daily_rate'          => $calculatedData['daily_rate'] ?? 0,

            'status' => 'update and approved' ?? 'not approved',
        ]);

        return redirect()->route('admin.salaryslip.index', $monthRecord->id)
            ->with('success', 'Salary slip update and approved successfully.');
    }

    /**
     * Approve the salary slip
     */
    public function approve(MonthRecord $monthRecord)
    { 
        $results =[
            'working_hours'=> $monthRecord->working_hours,
            'working_days'=> $monthRecord->working_days,
            'half_days'=> $monthRecord->half_days,
            'leaves' => $monthRecord->leaves,
            'sandwitch' => $monthRecord->sandwitch,
            'wfh' => $monthRecord->wfh,
            'wfh_dates' => $monthRecord->wfh_dates,
            'wfh_percentages' => $monthRecord->wfh_percentages,
        ];

         $calculatedData = $this->salCalService->calculateMonthlySalary(
                $monthRecord->user_id,
                $monthRecord->month,
                $monthRecord->year,
                $results,
                $monthRecord
        );

        $monthRecord->update([
            // 📅 Attendance & Leave
            'working_hours'   => $calculatedData['working_hours'] ?? 0,
            'working_days'   => $calculatedData['working_days'] ?? 0,
            'half_days'       => $calculatedData['half_days'] ?? 0,
            'leaves'          => $calculatedData['leaves'] ?? 0,
            'sandwitch'       => $calculatedData['sandwitch'] ?? 0,
            'wfh'             => $calculatedData['wfh'] ?? 0,

            // 💻 WFH details
            'wfh_dates'       => $calculatedData['wfh_dates'] ?? null,
            'wfh_percentages' => $calculatedData['wfh_percentages'] ?? [],
            'wfh_prices'      => $calculatedData['wfh_prices'] ?? [],
            'total_wfh_cost'  => $calculatedData['total_wfh_cost'] ?? 0,

            // 💰 Salary structure
            'gross_salary'     => $calculatedData['gross_salary'] ?? 0,
            'basic'            => $calculatedData['basic'] ?? 0,
            'hra'              => $calculatedData['hra'] ?? 0,
            'conveyance'       => $calculatedData['conveyance'] ?? 0,
            'simple_allowance' => $calculatedData['simple_allowance'] ?? 0,
            'other_allowance'  => $calculatedData['other_allowance'] ?? 0,

            // 🧾 CL info
            'used_cl'      => $calculatedData['used_cl'] ?? 0,
            'available_cl' => $calculatedData['available_cl'] ?? 0,

            // 💸 Deductions & net salary
            'leave_deduction'     => $calculatedData['leave_deduction'] ?? 0,
            'half_day_deduction'  => $calculatedData['half_day_deduction'] ?? 0,
            'total_deduction'     => $calculatedData['total_deduction'] ?? 0,
            'net_salary'          => $calculatedData['net_salary'] ?? 0,
            'daily_rate'          => $calculatedData['daily_rate'] ?? 0,

            'status' => 'approved' ?? 'not approved',
        ]);

        return redirect()->route('admin.salaryslip.index', $monthRecord->id)
            ->with('success', 'Salary slip approved successfully.');
    }

    public function pdfData(MonthRecord $monthRecord)
    {
        dd($monthRecord);
        $user = $monthRecord->user;

        // Load your PDF Blade view
        $pdf = PDF::loadView('Admin.salaryslip.slippdf', [
            'monthRecord' => $monthRecord
        ])
        ->setPaper('a4')
        ->setOption('margin-top', 10)
        ->setOption('margin-bottom', 10)
        ->setOption('encoding', 'UTF-8')
        ->setOption('enable-local-file-access', true);

        // Return inline view or download
        return $pdf->inline('SalarySlip-'.$monthRecord->user->name.'-'.$monthRecord->month.'.pdf');

        // return $pdf->download("SalarySlip_{$user->name}_{$monthRecord->monthName}_{$monthRecord->year}.pdf");
    }

}
