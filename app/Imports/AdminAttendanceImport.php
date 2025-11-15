<?php

namespace App\Imports;

use App\Models\AdminAttendance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use App\Models\User;

class AdminAttendanceImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Find the user by employee_code
        $user = User::where('employee_code', trim($row['employee_code']))->first();

        if (!$user) {
            return null; // skip row if user not found
        }

        // Parse date
        $date = Carbon::parse($row['date'])->format('Y-m-d');

        // Parse check_in and check_out
        $check_in = isset($row['check_in']) ? Carbon::parse($date.' '.$row['check_in']) : null;
        $check_out = isset($row['check_out']) ? Carbon::parse($date.' '.$row['check_out']) : null;

        // Calculate working hours and status
        $working_hours = null;
        $status = 'absent';

        if ($check_in && $check_out) {
            $working_hours = round($check_in->diffInMinutes($check_out) / 60, 2);

            if ($working_hours >= 9) {
                $status = 'present';
            } elseif ($working_hours >= 4.5) {
                $status = 'half_day';
            }
        }

        return new AdminAttendance([
            'user_id' => $user->id,
            'date' => $date,
            'check_in' => $check_in,
            'check_out' => $check_out,
            'day' => Carbon::parse($date)->format('l'),
            'working_hours' => $working_hours,
            'status' => $status,
        ]);
    }
}


// namespace App\Imports;

// use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Carbon\Carbon;

// class AdminAttendanceImport implements ToCollection, WithHeadingRow
// {
//     public $data = []; // Store all rows

//     public function collection(Collection $rows)
//     {
//         foreach ($rows as $row) {
//             $date = isset($row['date']) ? Carbon::parse($row['date'])->format('Y-m-d') : null;
//             $check_in = isset($row['check_in']) ? Carbon::parse($row['check_in']) : null;
//             $check_out = isset($row['check_out']) ? Carbon::parse($row['check_out']) : null;
//             $dayName = $date ? Carbon::parse($date)->format('l') : null;

//             $working_hours = null;
//             $status = 'Absent';
//             if ($check_in && $check_out) {
//                 $working_hours = round(($check_in->diffInMinutes($check_out)) / 60, 2);
//                 if ($working_hours >= 9) $status = 'Full Day';
//                 elseif ($working_hours >= 4.5) $status = 'Half Day';
//             }

//             $this->data[] = [
//                 'employee_id' => $row['employee_code'] ?? null,
//                 'date' => $date,
//                 'check_in' => $check_in ? $check_in->format('H:i:s') : null,
//                 'check_out' => $check_out ? $check_out->format('H:i:s') : null,
//                 'working_hours' => $working_hours,
//                 'status' => $status,
//                 'dayName' => $dayName,
//             ];
//         }
//     }
// }