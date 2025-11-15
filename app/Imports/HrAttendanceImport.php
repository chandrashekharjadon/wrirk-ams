<?php

namespace App\Imports;

use App\Models\HrAttendance;
use App\Models\Holiday;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use App\Models\User;

class HrAttendanceImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 🧩 1️⃣ Find the user by employee_code
        $user = User::where('employee_code', trim($row['employee_code']))->first();

        if (!$user) {
            return null; // skip row if user not found
        }

        // 🧩 2️⃣ Parse date
        $date = Carbon::parse($row['date'])->format('Y-m-d');

        // 🧩 3️⃣ Parse check_in and check_out
        $check_in = isset($row['check_in']) && !empty($row['check_in'])
            ? Carbon::parse($date . ' ' . $row['check_in'])
            : null;

        $check_out = isset($row['check_out']) && !empty($row['check_out'])
            ? Carbon::parse($date . ' ' . $row['check_out'])
            : null;

        // 🧩 4️⃣ Default values
        $working_hours = null;
        $status = 'absent';

        // 🧩 5️⃣ Check for Holiday
        $holiday = Holiday::whereDate('date', $date)->first();

        if ($holiday) {
            if ($holiday->type === 'public') {
                $status = 'holiday'; // Everyone gets public holidays
            } elseif ($holiday->type === 'restricted') {
                // Restricted: Only for assigned users
                if ($holiday->users()->where('user_id', $user->id)->exists()) {
                    $status = 'holiday';
                }
            }
        }

        // 🧩 6️⃣ If not a holiday, check attendance logic
        if ($status !== 'holiday' && $check_in && $check_out) {
            $working_hours = round($check_in->diffInMinutes($check_out) / 60, 2);

            if (strtolower($row['status'] ?? '') === 'wfh') {
                $status = 'wfh';
            } elseif (strtolower($row['status'] ?? '') === 'sunday') {
                $status = 'sunday';
            } elseif ($working_hours >= 9) {
                $status = 'present';
            } elseif ($working_hours >= 4.5) {
                $status = 'half_day';
            } else {
                $status = 'absent';
            }
        }

        // 🧩 7️⃣ Return attendance model
        return new HrAttendance([
            'user_id'        => $user->id,
            'date'           => $date,
            'check_in'       => $check_in,
            'check_out'      => $check_out,
            'day'            => Carbon::parse($date)->format('l'),
            'working_hours'  => $working_hours,
            'status'         => $status,
            'wfh_percentage' => $row['wfh_percentage'] ?? null,
            'remark'         => $row['remark'] ?? null,
        ]);
    }
}
