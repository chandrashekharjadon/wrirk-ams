<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Holiday;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalEmployees'    => User::where('role', 'employee')->count(),
            'totalDepartments'  => Department::count(),
            'totalDesignations' => Designation::count(),
            'totalHolidays'     => Holiday::count(),
        ];

        return view('Admin.dashboard.dashboard', $data);
    }
}
