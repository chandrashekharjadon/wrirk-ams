<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\AdminAttendance;
use App\Models\FinalAttendance;
use App\Models\HrAttendance;
use App\Models\Wfh;
use App\Models\Leave;
use App\Models\Holiday;

class AttendanceReportService
{
    /**
     * Get full month data week-wise for a user
     */
    public function getWeeklyReport(int $userId, int $month, int $year): array
    {
        $findWeekWiseData = $this->findWeekWiseData($month, $year);
        $mergedAttendance = $this->mergeAttendanceData($userId, $findWeekWiseData);
        $getweekdata = $this->logicMontoSat($month, $mergedAttendance);

        return $getweekdata;
    }

    /**
     * Get data range week-wise for the mon-sat get data...
     */
    private function findWeekWiseData($month, $year): array
    {
        $firstDay = Carbon::create($year, $month, 1);
        // dd($firstDay);
        $startDate = $firstDay->copy()->startOfWeek(Carbon::MONDAY); 
        // dd($startDate);

        // Last day of current month
        $endDate = $firstDay->copy()->endOfMonth();

        if (!$endDate->isWeekend()) {
            $endDate = $endDate->copy()->endOfWeek(Carbon::SATURDAY);
        }

        return [$startDate, $endDate];
    }

    /**
     * Get all leave dates for a user (expanded between start and end)
     */
    private function getLeaveDates(int $userId, array $findWeekWiseData ): array
    {
        $startDate = $findWeekWiseData[0]; // Carbon instance
        $endDate = $findWeekWiseData[1];   // Carbon instance

        $leaves = Leave::where('user_id', $userId)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();

        $allLeaveDates = [];
        foreach ($leaves as $leave) {

            // dd($leave);
            $allLeaveDates[] = [
                    'date' => $leave->date->format('Y-m-d'),
                    'day' => $leave->date->format('l'),
                    'check_in' => '-',   
                    'check_out' => '-',  
                    'status' => 'absent',
                    'working_hours' => 0
                ];
        }

        // Remove duplicate dates (in case of overlapping leaves)
        $allLeaveDates = array_values(array_unique($allLeaveDates, SORT_REGULAR));

        return $allLeaveDates;
    }

    /**
     * Get all WFH dates
     */
    private function getWfhDates(int $userId,  array $findWeekWiseData): array
    {
        $startDate = $findWeekWiseData[0]; // Carbon instance
        $endDate = $findWeekWiseData[1];   // Carbon instance

        $wfhRecords = Wfh::where('user_id', $userId)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();

        $wfhData = $wfhRecords->map(function ($wfh) {
            $checkIn = $wfh->check_in ?? '09:00:00';   // default if not provided
            $checkOut = $wfh->check_out ?? '18:00:00'; // default if not provided

            $workingHours = 0;
            if ($checkIn && $checkOut) {
                $checkInTime = Carbon::parse($checkIn);
                $checkOutTime = Carbon::parse($checkOut);
                $workingHours = round($checkInTime->diffInMinutes($checkOutTime) / 60, 2); // hours with 2 decimals
            }

            return [
                'date' => Carbon::parse($wfh->date)->format('Y-m-d'),
                'day' =>  Carbon::parse($wfh->date)->format('l'),
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'working_hours' => $workingHours,
                'status' => 'wfh', // manual status
            ];
        });

        return $wfhData->toArray();
    }


    /**
     * Get biometric attendance data
     */
    private function getBiometricAttendance(int $userId, array $findWeekWiseData): array
    {
        $startDate = $findWeekWiseData[0]; // Carbon instance
        $endDate = $findWeekWiseData[1];   // Carbon instance
        
        $attendance = FinalAttendance::where('user_id', $userId)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();
            
        return $attendance->map(function ($att) {
            if($att->status === 'holiday'){
                $att->check_in = '09:00:00';
                $att->check_out = '18:00:00';
                $att->working_hours = 9;
            } 

            return [
                'date' => Carbon::parse($att->date)->format('Y-m-d'),
                'day' => Carbon::parse($att->date)->format('l'),
                'check_in' => $att->check_in,
                'check_out' => $att->check_out,
                'status' => $att->status,
                'working_hours' => $att->working_hours,
            ];
        })->toArray();
    }

    /**
     * Merge all attendance data into one array sorted by date
     */
    private function mergeAttendanceData(int $userId, array $findWeekWiseData): array
    {
        // Get all individual data
        $leaveDates = $this->getLeaveDates($userId, $findWeekWiseData);
        $wfhDates = $this->getWfhDates($userId, $findWeekWiseData);
        $biometric = $this->getBiometricAttendance($userId, $findWeekWiseData);

        // Merge all arrays
        $allData = array_merge($leaveDates, $wfhDates, $biometric);

        // Sort by date ascending
        sort($allData);

        // Reset keys to 0,1,2...
        // return array_values($allData);

         // saurabh write....
         $merged = array_values($allData);

         // ✅ Fill Sundays and missing days
         return $this->fillMissingDays($merged, $findWeekWiseData);
    }

    /**
     * Logic mon-sat leaves and presents...
     */
    private function applySandwichLeaveRule(array $attendance): array
    {
        $count = count($attendance);

        for ($i = 0; $i < $count; $i++) {

            // Skip if already leave or sandwitch
            if (in_array($attendance[$i]['status'], ['absent', 'sandwitch'])) continue;

            // Only consider Sunday or holiday
            if (in_array($attendance[$i]['status'], ['sunday', 'holiday'])) {

                // Find previous leave
                $prevLeaveIndex = null;
                for ($p = $i - 1; $p >= 0; $p--) {
                    if ($attendance[$p]['status'] === 'absent') {
                        $prevLeaveIndex = $p;
                        break;
                    } elseif (!in_array($attendance[$p]['status'], ['sunday', 'holiday'])) {
                        break;
                    }
                }

                // Find next leave
                $nextLeaveIndex = null;
                for ($n = $i + 1; $n < $count; $n++) {
                    if ($attendance[$n]['status'] === 'absent') {
                        $nextLeaveIndex = $n;
                        break;
                    } elseif (!in_array($attendance[$n]['status'], ['sunday', 'holiday'])) {
                        break;
                    }
                }

                // Mark all intermediate days as sandwich
                if (!is_null($prevLeaveIndex) && !is_null($nextLeaveIndex)) {
                    for ($k = $prevLeaveIndex + 1; $k < $nextLeaveIndex; $k++) {
                        if (!in_array($attendance[$k]['status'], ['absent', 'sandwitch'])) {
                            $attendance[$k]['status'] = 'sandwitch';
                            $attendance[$k]['working_hours'] = 0;
                        }
                    }
                }
            }
        }

        return $attendance;
    }

    /**
     * Logic mon-sat leaves and presents...
     */
    private function logicMontoSat(int $month, array $mergedAttendance): array
    {
        $mergedAttendance = $this->applySandwichLeaveRule($mergedAttendance);

        $weeks = [];
        $monthSummary = [
            'total_working_hours' => 0,
            'total_working_days' => 0,
            'total_half_days' => 0,
            'total_leaves' => 0,
            'total_sandwitch' => 0,
            'total_wfh' => 0,
            'all_wfh_dates' => [], // ✅ all-month WFH dates
        ];

        $week = [
            'week_days' => [],
            'summary' => [
                'working_hours' => 0,
                'working_days' => 0,
                'half_days' => 0,
                'leaves' => 0,
                'sandwitch' => 0,
                'wfh' => 0,
                'wfh_dates' => [], // ✅ per-week WFH dates
                'met_54h' => false,
            ]
        ];

        foreach ($mergedAttendance as $item) {
            $week['week_days'][] = [
                'date' => $item['date'],
                'day' => $item['day'],
                'check_in' => $item['check_in'],
                'check_out' => $item['check_out'],
                'hours' => $item['working_hours'],
                'status' => $item['status'],
            ];

            // ➕ Accumulate working hours
            $week['summary']['working_hours'] += $item['working_hours'];

            // 🟢 Count working days
            if (in_array($item['status'], ['present', 'half_day', 'wfh'])) {
                $week['summary']['working_days']++;
            }

            // 🧩 If WFH, store its date
            if ($item['status'] === 'wfh') {
                $week['summary']['wfh']++;
                $week['summary']['wfh_dates'][] = $item['date'];
                $monthSummary['all_wfh_dates'][] = $item['date'];
            }

            // 🧩 End of week (Saturday)
            if ($item['day'] === 'Saturday') {
                $week['summary']['met_54h'] = $week['summary']['working_hours'] >= 54;

                // ✅ Count leaves etc. ONLY if total < 54 hours
                if (!$week['summary']['met_54h']) {
                    foreach ($week['week_days'] as $dayItem) {
                        if (Carbon::parse($dayItem['date'])->month === $month) {
                            if ($dayItem['status'] === 'half_day') $week['summary']['half_days']++;
                            if ($dayItem['status'] === 'absent') $week['summary']['leaves']++;
                            if ($dayItem['status'] === 'sandwitch') $week['summary']['sandwitch']++;
                        }
                    }
                }

                // Push week to list
                $weeks[] = $week;

                // Update month totals
                $monthSummary['total_working_hours'] += $week['summary']['working_hours'];
                $monthSummary['total_working_days'] += $week['summary']['working_days']; // 🟢 added
                $monthSummary['total_half_days'] += $week['summary']['half_days'];
                $monthSummary['total_leaves'] += $week['summary']['leaves'];
                $monthSummary['total_sandwitch'] += $week['summary']['sandwitch'];
                $monthSummary['total_wfh'] += $week['summary']['wfh'];

                // Reset week
                $week = [
                    'week_days' => [],
                    'summary' => [
                        'working_hours' => 0,
                        'working_days' => 0, // 🟢 added
                        'half_days' => 0,
                        'leaves' => 0,
                        'sandwitch' => 0,
                        'wfh' => 0,
                        'wfh_dates' => [],
                        'met_54h' => false,
                    ]
                ];
            }
        }

        // Handle last (incomplete) week
        if (!empty($week['week_days'])) {
            $week['summary']['met_54h'] = $week['summary']['working_hours'] >= 54;

            if (!$week['summary']['met_54h']) {
                foreach ($week['week_days'] as $dayItem) {
                    if (Carbon::parse($dayItem['date'])->month === $month) {
                        if ($dayItem['status'] === 'half_day') $week['summary']['half_days']++;
                        if ($dayItem['status'] === 'absent') $week['summary']['leaves']++;
                        if ($dayItem['status'] === 'sandwitch') $week['summary']['sandwitch']++;
                    }
                }
            }

            $weeks[] = $week;

            // Add to month totals
            $monthSummary['total_working_hours'] += $week['summary']['working_hours'];
            $monthSummary['total_working_days'] += $week['summary']['working_days']; // 🟢 added
            $monthSummary['total_half_days'] += $week['summary']['half_days'];
            $monthSummary['total_leaves'] += $week['summary']['leaves'];
            $monthSummary['total_sandwitch'] += $week['summary']['sandwitch'];
            $monthSummary['total_wfh'] += $week['summary']['wfh'];
        }

        return [
            'weeks' => $weeks,
            'month_summary' => $monthSummary,
        ];
    }

    // saurabh create services
    private function fillMissingDays(array $mergedAttendance, array $findWeekWiseData): array
    {
    [$startDate, $endDate] = $findWeekWiseData;
    $dates = [];
    foreach ($mergedAttendance as $item) {
        $dates[$item['date']] = $item;
    }

    $filled = [];
    $current = $startDate->copy();

    while ($current->lte($endDate)) {
        $dateKey = $current->format('Y-m-d');

        if (isset($dates[$dateKey])) {
            $filled[] = $dates[$dateKey];
        } else {
            $filled[] = [
                'date' => $dateKey,
                'day' => $current->format('l'),
                'check_in' => '-',
                'check_out' => '-',
                'working_hours' => 0,
                'status' => $current->isSunday() ? 'sunday' : 'absent',
            ];
        }

        $current->addDay();
    }

    return $filled;
    }
    
    // saurabh create services
    private function getHolidayDates(array $findWeekWiseData): array
    {
    [$startDate, $endDate] = $findWeekWiseData;

    $holidays = Holiday::whereBetween('date', [
        $startDate->format('Y-m-d'),
        $endDate->format('Y-m-d'),
    ])->get();

    return $holidays->map(function ($holiday) {
        return [
            'date' => Carbon::parse($holiday->date)->format('Y-m-d'),
            'day' => Carbon::parse($holiday->date)->format('l'),
            'check_in' => '-',
            'check_out' => '-',
            'working_hours' => 0,
            'status' => 'holiday',
        ];
    })->toArray();
    }


}
