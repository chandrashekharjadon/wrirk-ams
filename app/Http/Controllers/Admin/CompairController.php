<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AdminAttendanceImport;
use App\Imports\HrAttendanceImport;
use Illuminate\Support\Facades\DB;
use App\Models\AdminAttendance;
use App\Models\HrAttendance;
use App\Models\FinalAttendance;
use App\Models\Wfh;
use App\Models\Leave;
use App\Models\Holiday;
use Carbon\Carbon;

class CompairController extends Controller
{
    public function index()
    {
       return view('Admin.compair.fileupload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $user = auth()->user();
            // 🧠 Choose import class based on user role
            $importClass = $user->isAdmin() 
                ? new AdminAttendanceImport 
                : ($user->isHR() ? new HrAttendanceImport : null);

            if (!$importClass) {
                return redirect()->back()->with('error', 'Unauthorized: Only Admin or HR can import attendance.');
            }

            Excel::import($importClass, $request->file('file'));

            return redirect()->back()->with('success', 'Attendance imported successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    // public function showcompairdata(Request $request)
    // {
    //     $employees = User::all();
    //     $mergedData = []; // ✅ Initialize this

    //     // if ($request->filled(['employee_id', 'month', 'date'])) {
    //     if ($request->filled(['date'])) {
    //         // dd($request->date);

    //         $month = Carbon::parse($request->month);
    //         $userId = $request->employee_id;

    //         $adminData = AdminAttendance::where('user_id', $userId)
    //             ->whereMonth('date', $month->month)
    //             ->whereYear('date', $month->year)
    //             ->get()
    //             ->keyBy('date');

    //         $hrData = HrAttendance::where('user_id', $userId)
    //             ->whereMonth('date', $month->month)
    //             ->whereYear('date', $month->year)
    //             ->get()
    //             ->keyBy('date');

    //         foreach ($adminData as $date => $adminRecord) {
    //             $mergedData[$date] = [
    //                 'admin' => $adminRecord,
    //                 'hr' => $hrData[$date] ?? null,
    //             ];
    //         }
    //     }

    //     return view('Admin.compair.matching', compact('mergedData', 'employees'));
    // }

    // public function showcompairdata(Request $request)
    // {
    //     $employees = User::all();
    //     $mergedData = []; // ✅ Initialize this

    //     // if ($request->filled(['employee_id', 'month', 'date'])) {
    //     if ($request->date) {
    //         // dd($request->date);

    //         $date = Carbon::parse($request->date);
    //         $month = Carbon::parse($request->month);
    //         $userId = $request->employee_id;

    //         $adminData = AdminAttendance::where('date', $date)
    //             ->get()
    //             ->groupBy('date');            

    //         $hrData = HrAttendance::where('date', $date)
    //             ->get()
    //             ->groupBy('date');

    //         foreach ($hrData as $date => $hrRecord) {
    //             $mergedData[$date] = [
    //                 'hr' => $hrRecord ?? null,
    //                 'admin' => $adminData[$date] ?? null,
    //             ];

    //         }
    //     }

    //     return view('Admin.compair.matching', compact('mergedData'));
    // }
    public function showcompairdata(Request $request)
    {
        $employees = User::all();
        $mergedData = []; // ✅ Initialize

        if ($request->filled('date')) {

            $date = Carbon::parse($request->date)->format('Y-m-d');

            // 🧩 Fetch Admin Attendance with related users
            $adminData = AdminAttendance::with('user')
                ->whereDate('date', $date)
                ->get()
                ->keyBy(fn($item) => $item->user?->employee_code);

            // 🧩 Fetch HR Attendance with related users
            $hrData = HrAttendance::with('user')
                ->whereDate('date', $date)
                ->get()
                ->keyBy(fn($item) => $item->user?->employee_code);

            // 🧩 Merge both datasets
            $allEmployeeCodes = $adminData->keys()->merge($hrData->keys())->unique();

            foreach ($allEmployeeCodes as $code) {
                $mergedData[$code] = [
                    'admin' => $adminData[$code] ?? null,
                    'hr'    => $hrData[$code] ?? null,
                ];
            }
        }

        return view('Admin.compair.matching', compact('mergedData'));
    }



    // public function saveFinal(Request $request)
    // {
    //     $records = $request->input('records', []);
        
    //     dd($records);
        
    //     if (empty($records)) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'No records received.'
    //         ]);
    //     }

    //     try {
    //         foreach ($records as $record) {
    //             // Try to find the user by employee code or name
    //             $user = User::where('employee_code', $record['employee_code'] ?? null)
    //                         ->orWhere('name', $record['employee_name'] ?? null)
    //                         ->first();

    //             DB::table('final_attendance')->insert([
    //                 'user_id' => $user?->id ?? 1, // fallback if user not found
    //                 'date' => Carbon::parse($record['date'])->format('Y-m-d'),
    //                 'check_in' => $record['check_in'] !== '' ? $record['check_in'] : null,
    //                 'check_out' => $record['check_out'] !== '' ? $record['check_out'] : null,
    //                 'day' => Carbon::parse($record['date'])->format('l'),
    //                 'working_hours' => is_numeric($record['work_hours']) 
    //                     ? $record['work_hours'] 
    //                     : (float) preg_replace('/[^0-9.]/', '', $record['work_hours']),
    //                 'status' => strtolower($record['status'] ?? 'absent'),
    //                 'source' => strtoupper($record['source']),
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Records saved successfully!'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Error saving records: ' . $e->getMessage(),
    //         ]);
    //     }
    // }

    public function saveFinal(Request $request)
    {
        $records = $request->input('records', []);

        if (empty($records)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No records received.'
            ]);
        }

        DB::beginTransaction();

        try {
            $alreadyFinalized = [];

            foreach ($records as $record) {
                // 🧩 Find the user
                $user = User::where('employee_code', $record['employee_code'] ?? null)
                            ->orWhere('name', $record['employee_name'] ?? null)
                            ->first();

                if (!$user) continue;

                $date = Carbon::parse($record['date'])->format('Y-m-d');

                // 🔍 Check if record already finalized for this user + date
                $existsInFinal = FinalAttendance::where('user_id', $user->id)
                    ->where('date', $date)
                    ->exists();

                $existsInWfh = Wfh::where('user_id', $user->id)
                    ->where('date', $date)
                    ->exists();

                $existsInLeave = Leave::where('user_id', $user->id)
                    ->where('date', $date)
                    ->exists();

                if ($existsInFinal || $existsInWfh || $existsInLeave) {
                    $alreadyFinalized[] = "{$record['employee_name']} ({$record['date']})";
                    continue;
                }


                // Normalize common fields
                $checkIn  = !empty($record['check_in']) ? $record['check_in'] : null;
                $checkOut = !empty($record['check_out']) ? $record['check_out'] : null;
                $status   = strtolower($record['status'] ?? 'absent');
                $remark   = $record['remark'] ?? null;

                // 🧠 Decide which table to save in
                switch ($status) {
                    case 'wfh':
                        // 💾 Save in WFH table
                        Wfh::create([
                            'user_id'   => $user->id,
                            'date'      => $date,
                            'percent'   => $record['wfh_percentage'] ?? 100,
                            'check_in'  => $checkIn,
                            'check_out' => $checkOut,
                            'remark'    => $remark,
                        ]);
                        break;

                    case 'absent':
                        // 💾 Save in Leaves table
                        Leave::create([
                            'user_id' => $user->id,
                            'date'    => $date,
                            'reason'  => $remark ?? 'Absent',
                        ]);
                        break;

                    case 'present':
                    default:
                        // 💾 Save in Final Attendance
                        FinalAttendance::create([
                            'user_id'       => $user->id,
                            'date'          => $date,
                            'day'           => Carbon::parse($date)->format('l'),
                            'check_in'      => $checkIn,
                            'check_out'     => $checkOut,
                            'working_hours' => is_numeric($record['work_hours'])
                                ? $record['work_hours']
                                : (float) preg_replace('/[^0-9.]/', '', $record['work_hours']),
                            'status'        => $status,
                            'source'        => strtoupper($record['source'] ?? 'MANUAL'),
                            'remark'        => $remark,
                        ]);
                        break;
                }
            }

            DB::commit();

            // ⚠️ Some records were skipped
            if (count($alreadyFinalized) > 0) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Some records were already finalized: ' . implode(', ', $alreadyFinalized),
                ]);
            }

            // ✅ Success
            return response()->json([
                'status' => 'success',
                'message' => 'Records finalized successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error saving records: ' . $e->getMessage(),
            ]);
        }
    }

    

}
