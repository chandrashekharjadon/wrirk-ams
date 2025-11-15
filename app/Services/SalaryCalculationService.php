<?php

namespace App\Services;

use App\Models\User;

class SalaryCalculationService
{
    /**
     * Main function to calculate monthly salary
     */
    public function calculateMonthlySalary($userId, $month, $year, $results=null, $monthRecord=null)
    {
        // 🧩 Step 1️⃣: Get user and salary info
        [$user, $salary, $grossSalary, $dailyRate] = $this->getUserSalaryInfo($userId);

        // 🧮 Step 2️⃣: Extract monthly data
        $attendance = $this->getResultData($results, $monthRecord);

        // 🏠 Step 3️⃣: WFH Calculation
        $wfhData = $this->calculateWfhCost($user, $attendance['wfhDates'], $attendance['wfhPercentages'], $dailyRate, $monthRecord);

        // 🌴 Step 4️⃣: Adjust CL (Casual Leave)
        $clAdjustment = $this->adjustCasualLeave($user, $attendance['allLeaves'], $monthRecord);

        // dd($clAdjustment);

        // 💸 Step 5️⃣: Deduction Calculation (Leaves + Half-days)
        $deductions = $this->calculateDeductions($clAdjustment['allLeaves'] ?? 0, $dailyRate, $wfhData['total_detected_wfh_cost'] ?? 0);

        // 💰 Step 6️⃣: Final Net Salary
        $netSalary = ($grossSalary - $deductions['total_deduction']) + $wfhData['total_wfh_cost'];

        // ✅ Step 7️⃣: Return final report
        return [
            'gross_salary'     => round($grossSalary, 2),
            'basic'            => round($salary->basic, 2),
            'hra'              => round($salary->hra, 2),
            'conveyance'       => round($salary->conveyance, 2),
            'simple_allowance' => round($salary->simple_allowance, 2),
            'other_allowance'  => round($salary->other_allowance, 2),

            // Attendance Info
            'working_hours' => $attendance['totalWorkingHours'] ?? 0,
            'working_days' => $attendance['totalWorkingDays'] ?? 0,
            'leaves'        => $attendance['totalLeaves'] ?? 0,
            'half_days'     => $attendance['totalHalfDays'] ?? 0,
            'wfh'           => $attendance['totalWfh'] ?? 0,
            'sandwitch'     => $attendance['totalSandwitch'] ?? 0,

            // CL Info
            'used_cl'          => $clAdjustment['used'] ?? 0,
            'available_cl'     => $clAdjustment['remaining'] ?? 0,

            // Deductions
            'leave_deduction'     => round($deductions['leave_deduction'], 2),
            'half_day_deduction'  => round($deductions['half_day_deduction'], 2),
            'wfh_detection'  => round($deductions['wfh_detection'], 2),
            'total_deduction'     => round($deductions['total_deduction'], 2),

            // WFH
            'wfh_dates'        => $wfhData['dates'],
            'wfh_percentages'  => $wfhData['percentages'],
            'wfh_prices'       => $wfhData['prices'],
            'total_wfh_cost'   => round($wfhData['total_wfh_cost'], 2),

            // Final
            'net_salary'       => round($netSalary, 2),
            'daily_rate'       => round($dailyRate, 2),
        ];
       
    }

    // 🧩---------------------------------------
    //  Get user & salary info
    // ----------------------------------------
    private function getUserSalaryInfo($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            throw new \Exception("User not found");
        }

        $salary = $user?->userSalary;
        $grossSalary = $salary?->gross_salary ?? 0;
        $dailyRate = $grossSalary / 30;

        return [$user, $salary, $grossSalary, $dailyRate];
    }

    // 🧮---------------------------------------
    //  Extract attendance result data
    // ----------------------------------------
    private function getResultData($results, $monthRecord)
    {
        $totalWorkingHours = 0;
        $totalWorkingDays = 0;
        $totalLeaves    = 0;
        $totalHalfDays  = 0;
        $totalSandwitch = 0;
        $totalWfh       = 0;
        $wfhDates       = [];
        $wfhPercentages = [];
        // Combine all leaves
        $totalLeavesCombined = $totalLeaves + $totalSandwitch + $totalHalfDays;
            
        if($monthRecord){
            $totalWorkingHours = $results['working_hours'] ?? 0;
            $totalWorkingDays = $results['working_days'] ?? 0;
            $totalLeaves    = $results['leaves'] ?? 0;
            $totalHalfDays  = $results['half_days'] ?? 0;
            $totalSandwitch = $results['sandwitch'] ?? 0;
            $totalWfh       = $results['wfh'] ?? 0;
            $wfhDates       = $results['wfh_dates'] ?? [];
            $wfhPercentages       = $results['wfh_percentages'] ?? [];

            // Combine all leaves
            $totalLeavesCombined = $totalLeaves + $totalSandwitch + ($totalHalfDays/2);
        } else{
            $totalWorkingHours = $results['month_summary']['total_working_hours'] ?? 0;
            $totalWorkingDays = $results['month_summary']['total_working_days'] ?? 0;
            $totalLeaves    = $results['month_summary']['total_leaves'] ?? 0;
            $totalHalfDays  = $results['month_summary']['total_half_days'] ?? 0;
            $totalSandwitch = $results['month_summary']['total_sandwitch'] ?? 0;
            $totalWfh       = $results['month_summary']['total_wfh'] ?? 0;
            $wfhDates       = $results['month_summary']['all_wfh_dates'] ?? [];
            $wfhPercentages       = $results['month_summary']['all_wfh_percentages'] ?? [];
            // Combine all leaves
            $totalLeavesCombined = $totalLeaves + $totalSandwitch + ($totalHalfDays/2);

        }
        
        return [
            'totalWorkingHours' => $totalWorkingHours,
            'totalWorkingDays' => $totalWorkingDays,
            'totalLeaves' => $totalLeaves,
            'totalHalfDays' => $totalHalfDays,
            'totalSandwitch' => $totalSandwitch,
            'allLeaves'   => $totalLeavesCombined,
            'totalWfh'      => $totalWfh,
            'wfhDates'      => $wfhDates,
            'wfhPercentages'      => $wfhPercentages,
        ];
    }

    // 🏠---------------------------------------
    //  Calculate WFH cost
    // ----------------------------------------
    private function calculateWfhCost($user, $wfhDates, $wfhPercentages, $dailyRate, $monthRecord)
    {
        $percentages = [];
        $prices = [];
        $dates = [];
        $totalCost = 0;
        $totalDetectionCost = 0;

        // ✅ If updating existing month record (edit mode)
        if ($monthRecord) {
            foreach ($wfhDates as $index => $date) {
                $percent = $wfhPercentages[$index] ?? 0;
                $cost = ($dailyRate * $percent) / 100;

                $dates[] = $date;
                $percentages[] = $percent;
                $prices[] = round($cost, 2);
                $totalCost += $cost;

                $detectedpercent = (100-$wfhPercentages[$index]) ?? 0;
                $detectionCost = ($dailyRate * $detectedpercent) / 100;
                $totalDetectionCost += $detectionCost;
            }
        } 
        // ✅ If creating new month record (fetch from DB)
        else {
            $records = $user->wfh()->whereIn('date', $wfhDates)->get();

            foreach ($records as $r) {
                $percent = $r->percent ?? 0;
                $cost = ($dailyRate * $percent) / 100;

                $dates[] = $r->date;
                $percentages[] = $percent;
                $prices[] = round($cost, 2);
                $totalCost += $cost;

                $detectedpercent = (100 - $r->percent) ?? 0;
                $detectionCost = ($dailyRate * $detectedpercent) / 100;
                $totalDetectionCost += $detectionCost;
            }
        }

        return [
            'percentages' => $percentages,
            'prices' => $prices,
            'dates' => $dates,
            'total_wfh_cost' => round($totalCost, 2),
            'total_detected_wfh_cost' => round($totalDetectionCost, 2),
        ];
    }


    // 🌴---------------------------------------
    //  Adjust casual leave balance
    // ----------------------------------------
    private function adjustCasualLeave($user, $allLeaves, $monthRecord)
    {
        $cl = $user->cl;
        if (!$cl) {
            return ['used' => 0, 'remaining' => 0];
        }

        $available = $cl->remaining ?? 0;
        $total = $cl->total ?? 0;
        $used = $cl->used ?? 0;
        $usedNow = 0;

        if($monthRecord){
             if ($allLeaves <= $available) {
                $allLeaves = round($allLeaves);
                $usedNow = $allLeaves;
                $available -= $allLeaves;
                $allLeaves = 0;
            } else {
                $usedNow = $available;
                $allLeaves -= $available;
                $available = 0;
            }

            $cl->used = $used + $usedNow;
            $cl->remaining = $total - $cl->used;
            $cl->save();
        } else{
            $allLeaves = $allLeaves;
        }

        return [
            'used' => $cl->used,
            'remaining' => $cl->remaining,
            'allLeaves' => $allLeaves,
        ];
    }

    // 💸---------------------------------------
    //  Calculate deductions (Leaves + Half-days)
    // ----------------------------------------
    private function calculateDeductions($allLeaves, $dailyRate, $wfhdetectioncost)
    {
        $fullLeaves = floor($allLeaves);
        $decimalPart = fmod($allLeaves, 1);
        $halfDays = $decimalPart == 0.5 ? 1 : 0;

        $leaveDeduction = $fullLeaves * $dailyRate;
        $halfDayDeduction = $halfDays * ($dailyRate / 2);
        $totalDeduction = $allLeaves > 0 ? ($leaveDeduction + $halfDayDeduction + $wfhdetectioncost) : 0;

        // dd($halfDays, $leaveDeduction, $totalDeduction);

        return [
            'leave_deduction'     => $leaveDeduction,
            'half_day_deduction'  => $halfDayDeduction,
            'wfh_detection'  => $wfhdetectioncost,
            'total_deduction'     => $totalDeduction,
        ];
    }
}
