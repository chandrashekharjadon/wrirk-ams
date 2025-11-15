<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSalary;

class UserSalaryController extends Controller
{
    public function index(Request $request)
    {
        $query = UserSalary::with('user');
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $UserSalary = $query->latest()->paginate(10);
        $users = User::all();
        return view('Admin.UserSalaries.index', compact('UserSalary','users'));
    }

    public function create()
    {
        $users = User::all();
        return view('Admin.UserSalaries.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:user_salaries,user_id',
            'gross_salary' => 'required|numeric|min:0',
        ]);

        $gross = $request->gross_salary;

        // 💰 Calculate based on percentage distribution
        $basic            = $gross * 0.50; // 50%
        $hra              = $gross * 0.20; // 20%
        $conveyance       = $gross * 0.10; // 10%
        $simple_allowance = $gross * 0.15; // 15%
        $other_allowance  = $gross * 0.05; // 05%

        // 🧾 Save
        UserSalary::create([
            'user_id'          => $request->user_id,
            'gross_salary'     => $gross,
            'basic'            => $basic,
            'hra'              => $hra,
            'conveyance'       => $conveyance,
            'simple_allowance' => $simple_allowance,
            'other_allowance'  => $other_allowance,
        ]);

        return redirect()->route('admin.UserSalaries.index')->with('success', 'Salary added successfully.');
    }

    public function edit(UserSalary $UserSalary)
    {
        $users = User::all();
        return view('Admin.UserSalaries.edit', compact('UserSalary', 'users'));
    }

    public function update(Request $request, UserSalary $UserSalary)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'gross_salary' => 'required|numeric|min:0',
        ]);

        $gross = $request->gross_salary;

        // 💰 Recalculate updated salary breakdown
        $basic            = $gross * 0.50; // 50%
        $hra              = $gross * 0.20; // 20%
        $conveyance       = $gross * 0.10; // 10%
        $simple_allowance = $gross * 0.15; // 15%
        $other_allowance  = $gross * 0.05; // 05%

        $UserSalary->update([
            'user_id'          => $request->user_id,
            'gross_salary'     => $gross,
            'basic'            => $basic,
            'hra'              => $hra,
            'conveyance'       => $conveyance,
            'simple_allowance' => $simple_allowance,
            'other_allowance'  => $other_allowance,
        ]);

        return redirect()->route('admin.UserSalaries.index')->with('success', 'Salary updated successfully.');
    }

    public function destroy(UserSalary $UserSalary)
    {
        $UserSalary->delete();
        return redirect()->route('admin.UserSalaries.index')->with('success', 'Salary deleted successfully.');
    }
}
