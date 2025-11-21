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

        //📈 calculate earning
        $earning = $this->calculateEarnings($grossSalary,$results, $monthRecord);

        // 💸 Step 5️⃣: Deduction Calculation (Leaves + Half-days)
        $deductions = $this->calculateDeductions($clAdjustment['allLeaves'] ?? 0, $attendance['totalHalfDays'] ?? 0, $dailyRate, $wfhData['total_detected_wfh_cost'] ?? 0,$results, $monthRecord);

        // 💰 Step 6️⃣: Final Net Salary
        $netSalary = ($earning['totalEarning'] - $deductions['total_deduction']);
        
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
            'leave_deduction'     => round($deductions['leave_deduction'], 2) ?? 0,
            'half_day_deduction'  => round($deductions['half_day_deduction'], 2) ?? 0,
            'wfh_detection'  => round($deductions['wfh_detection'], 2) ?? 0,
            'total_deduction'  => round($deductions['total_deduction'], 2) ?? 0,

            // WFH
            'wfh_dates'        => $wfhData['dates']?? 0,
            'wfh_percentages'  => $wfhData['percentages']?? 0,
            'wfh_prices'       => $wfhData['prices']?? 0,
            'total_wfh_cost'   => round($wfhData['total_wfh_cost'], 2) ?? 0,

            // Final
            'net_salary'       => round($netSalary, 2) ?? 0,
            'daily_rate'       => round($dailyRate, 2) ?? 0,

            //new record
            'pf'        => $deductions['pf'] ?? 0,
            'tds'        => $deductions['tds'] ?? 0,
            'alternative_variable_loses'        => $deductions['alternative_variable_loses'] ?? 0,
            
            'alternative_variable_pay'        => $earning['alternative_variable_pay'] ?? 0,
            'total_earning'        => $earning['totalEarning'] ?? 0,
            'incentive'        => $earning['incentive'] ?? 0,
            'reward'        => $earning['reward'] ?? 0,
            
            'total_cl'        => $clAdjustment['total_cl'] ?? 0,
            'wfh_deduction_cost'        => round($deductions['wfh_detection'], 2) ?? 0,

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
        $totalLeavesCombined = $totalLeaves + $totalSandwitch;
            
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
            $totalLeavesCombined = $totalLeaves + $totalSandwitch;
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
            $totalLeavesCombined = $totalLeaves + $totalSandwitch;

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
            'total_cl' => $cl->total,
            'allLeaves' => $allLeaves,
        ];
    }

    // 💸---------------------------------------
    //  Calculate deductions (Leaves + Half-days)
    // ------------------------------------------
    private function calculateDeductions($allLeaves, $allhalfdays, $dailyRate, $wfhdetectioncost, $results, $monthRecord)
    {
        $pf = 0;
        $tds = 0;
        $alternative_variable_loses =0;

        if($monthRecord){
            $alternative_variable_loses = $results['alternative_variable_loses'] ?? 0;
        }

        $total_final_pay = ($pf+$tds+$alternative_variable_loses);

        $leaveDeduction = $allLeaves * $dailyRate;
        $halfDayDeduction = $allhalfdays * ($dailyRate / 2);

        $totalDeduction = ($leaveDeduction + $halfDayDeduction + $wfhdetectioncost + $total_final_pay) ?? 0;

        return [
            'leave_deduction'     => $leaveDeduction,
            'half_day_deduction'  => $halfDayDeduction,
            'wfh_detection'  => $wfhdetectioncost,
            'pf'  => $pf,
            'tds'  => $tds,
            'alternative_variable_loses'  => $alternative_variable_loses,
            'total_deduction'     => $totalDeduction,
        ];
    }

    // 📈---------------------------------------
    //  Calculate Earning 
    // -----------------------------------------
    private function calculateEarnings($grossSalary, $results, $monthRecord)
    {
        $reward = 0;
        $incentive = 0;
        $alternative_variable_pay =0;

        if($monthRecord){
            $reward = $results['reward'] ?? 0;
            $incentive = $results['incentive'] ?? 0;
            $alternative_variable_pay = $results['alternative_variable_pay'] ?? 0;
        }

        $total_final_pay = ($reward+$incentive+$alternative_variable_pay);

        $totalEarning = ($grossSalary+$total_final_pay) ?? 0;

        return [
            'totalEarning'     => $totalEarning,
            'reward'  => $reward,
            'incentive'  => $incentive,
            'alternative_variable_pay'  => $alternative_variable_pay
        ];
    }
}
