<?php

use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WfhController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\CasualLeaveBalanceController;
use App\Http\Controllers\Admin\CompairController;
use App\Http\Controllers\Admin\AttendanceReportController;
use App\Http\Controllers\Admin\SalarySlipController;

// Employee Controllers
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\AttendanceController as EmployeeAttendanceController;
use App\Http\Controllers\Employee\HolidayController as EmployeeHolidayController;
use App\Http\Controllers\Employee\WfhController as EmployeeWfhController;
use App\Http\Controllers\Employee\SalarySlipController as EmployeeSalarySlipController;
use App\Http\Controllers\Employee\LeaveController as EmployeeLeaveController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('welcome'));

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Common Dashboard Redirect (based on role)
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        return match ($role) {
            'admin' => redirect()->route('employee.dashboard'),
            'hr' => redirect()->route('employee.dashboard'),
            'employee' => redirect()->route('employee.dashboard'),
            default => redirect('/login'),
        };
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin,hr'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            // Admin Dashboard
            // Route::get('/', function () {
            //     return redirect()->route('admin.wfhs.index');
            // })->name('dashboard');
            Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

            Route::resource('departments', DepartmentController::class);
            Route::resource('designations', DesignationController::class);
            Route::resource('users', UserController::class);
            Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
            Route::resource('wfhs', WfhController::class);
            Route::resource('holidays', HolidayController::class);
            Route::resource('leaves', LeaveController::class)->parameters(['leaves' => 'leave']);
            Route::resource('casual_leave_balances', CasualLeaveBalanceController::class);


            Route::get('fileupload', [CompairController::class, 'index'])->name('fileupload.index');
            Route::post('fileupload', [CompairController::class, 'store'])->name('fileupload.store');
            Route::get('compair/matching', [CompairController::class, 'showcompairdata'])->name('compair.matching');
            Route::post('compair/save-final', [CompairController::class, 'saveFinal'])->name('compair.saveFinal');

            
            Route::get('attendance', [AttendanceReportController::class, 'index'])->name('attendance.index');

            Route::get('salaryslip', [SalarySlipController::class, 'index'])->name('salaryslip.index');
            Route::get('salaryslip/{monthRecord}/edit', [SalarySlipController::class, 'edit'])->name('salaryslip.edit');
            Route::put('salaryslip/{monthRecord}', [SalarySlipController::class, 'update'])->name('salaryslip.update');
            Route::get('salaryslip/{monthRecord}/approve', [SalarySlipController::class, 'approve'])->name('salaryslip.approve');
            Route::get('salaryslip/{monthRecord}/pdf', [SalarySlipController::class, 'pdfData'])->name('salaryslip.pdf');
           
        });


    /*
    |--------------------------------------------------------------------------
    | Employee Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin,hr,employee'])
        ->prefix('employee')
        ->name('employee.')
        ->group(function () {

            // Dashboard
            Route::get('/', [EmployeeDashboardController::class, 'index'])->name('dashboard');

            // Attendance
            Route::get('/attendance', [EmployeeAttendanceController::class, 'index'])->name('attendance');

            // Holidays
            Route::get('/holidays', [EmployeeHolidayController::class, 'index'])->name('holidays');

            // Work From Home
            Route::get('/wfh', [EmployeeWfhController::class, 'index'])->name('wfh');

            // Salary Slip
            Route::get('/salary-slip', [EmployeeSalarySlipController::class, 'index'])->name('salaryslip');
            Route::get('/salary-slip/{monthRecord}/pdf', [EmployeeSalarySlipController::class, 'pdfData'])->name('salaryslip.pdf');

            // Leaves
            Route::get('/leaves', [EmployeeLeaveController::class, 'index'])->name('leaves');

             //  Profile View
            Route::get('/profile', [EmployeeDashboardController::class, 'profile'])->name('profile');
    });


});
