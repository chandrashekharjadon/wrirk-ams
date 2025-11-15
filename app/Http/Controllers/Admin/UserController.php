<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use App\Models\UserSalary;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        $users = $query->latest()->paginate(10);
        return view('Admin.users.index', compact('users'));
    }

    public function show($id)
    {
        // Fetch user by ID
        $user = User::findOrFail($id);

        // Return to Blade view
        return view('Admin.users.show', compact('user'));
    }

    public function create()
    {
        $departments = Department::all();
        $designations = Designation::all();
        $roles = ['admin','hr','employee'];
        return view('Admin.users.create', compact('departments','designations','roles'));
    }

    public function store(Request $request)
    {
        // VALIDATION
        $request->validate([
            // User
            'name'            => 'required|string|max:255|unique:users,name',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|string|min:6|confirmed',
            'employee_code'   => 'nullable|unique:users,employee_code',
            'role'            => 'required|in:admin,hr,employee',
            'department_id'   => 'nullable|exists:departments,id',
            'designation_id'  => 'nullable|exists:designations,id',

            // Profile
            'aadhar'          => 'nullable|string|max:50',
            'pan'             => 'nullable|string|max:50',
            'acc_no'          => 'nullable|string|max:50',
            'ifsc_code'       => 'nullable|string|max:20',
            'joining_date'    => 'nullable|date',
            'mobile'          => 'nullable|string|max:15',
            'address'         => 'nullable|string',
            'pin_code'        => 'nullable|string|max:10',
            'profile_photo'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Salary
            'gross_salary'    => 'required|numeric|min:0',
        ]);

        // UPLOAD PHOTO
        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profiles', 'public');
        }

        // CREATE USER
        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'employee_code'  => $request->employee_code,
            'role'           => $request->role,
            'department_id'  => $request->department_id,
            'designation_id' => $request->designation_id,
        ]);

        // CREATE PROFILE
        Profile::create([
            'user_id'       => $user->id,
            'aadhar'        => $request->aadhar,
            'pan'           => $request->pan,
            'acc_no'        => $request->acc_no,
            'ifsc_code'     => $request->ifsc_code,
            'joining_date'  => $request->joining_date,
            'mobile'        => $request->mobile,
            'address'       => $request->address,
            'pin_code'      => $request->pin_code,
            'profile_photo' => $photoPath,
            'status'        => 1,
        ]);

        $gross = $request->gross_salary;

        // Recalculate Salary Breakdown
        $basic            = $gross * 0.50;
        $hra              = $gross * 0.20;
        $conveyance       = $gross * 0.10;
        $simple_allowance = $gross * 0.15;
        $other_allowance  = $gross * 0.05;

        // CREATE SALARY
        UserSalary::create([
            'user_id'          => $user->id,
            'gross_salary'     => $request->gross_salary,
            'basic'            => $basic,
            'hra'              => $hra,
            'conveyance'       => $conveyance,
            'simple_allowance' => $simple_allowance,
            'other_allowance'  => $other_allowance,
        ]);

        return redirect()->route('admin.users.index')->with('success','User created successfully.');
    }

    public function edit(User $user)
    {
        $user->load(['Profile', 'userSalary']);

        $departments = Department::all();
        $designations = Designation::all();
        $roles = ['admin','hr','employee'];
        return view('Admin.users.edit', compact('user','departments','designations','roles'));
    }

    public function update(Request $request, User $user)
    {
        // VALIDATION
        $request->validate([
            // User
            'name'            => 'required|string|max:255|unique:users,name,' . $user->id,
            'email'           => 'required|email|unique:users,email,' . $user->id,
            'password'        => 'nullable|string|min:6|confirmed',
            'employee_code'   => 'nullable|unique:users,employee_code,' . $user->id,
            'role'            => 'required|in:admin,hr,employee',
            'department_id'   => 'nullable|exists:departments,id',
            'designation_id'  => 'nullable|exists:designations,id',

            // Profile
            'aadhar'          => 'nullable|string|max:50',
            'pan'             => 'nullable|string|max:50',
            'acc_no'          => 'nullable|string|max:50',
            'ifsc_code'       => 'nullable|string|max:20',
            'joining_date'    => 'nullable|date',
            'mobile'          => 'nullable|string|max:15',
            'address'         => 'nullable|string',
            'pin_code'        => 'nullable|string|max:10',
            'profile_photo'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Salary
            'gross_salary'    => 'required|numeric|min:0',
        ]);

        // UPDATE PROFILE PHOTO
        $photoPath = $user->profile ? $user->profile->profile_photo : null;

        if ($request->hasFile('profile_photo')) {

            // DELETE OLD PHOTO
            if ($photoPath && \Storage::disk('public')->exists($photoPath)) {
                \Storage::disk('public')->delete($photoPath);
            }

            // SAVE NEW PHOTO
            $photoPath = $request->file('profile_photo')->store('profiles', 'public');
        }

        // UPDATE USER
        $user->update([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => $request->password ? Hash::make($request->password) : $user->password,
            'employee_code'  => $request->employee_code,
            'role'           => $request->role,
            'department_id'  => $request->department_id,
            'designation_id' => $request->designation_id,
        ]);

        // UPDATE / CREATE PROFILE
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'aadhar'        => $request->aadhar,
                'pan'           => $request->pan,
                'acc_no'        => $request->acc_no,
                'ifsc_code'     => $request->ifsc_code,
                'joining_date'  => $request->joining_date,
                'mobile'        => $request->mobile,
                'address'       => $request->address,
                'pin_code'      => $request->pin_code,
                'profile_photo' => $photoPath,
                'status'        => 1,
            ]
        );

        // UPDATE SALARY
        $gross = $request->gross_salary;

        // Recalculate salary
        $basic            = $gross * 0.50;
        $hra              = $gross * 0.20;
        $conveyance       = $gross * 0.10;
        $simple_allowance = $gross * 0.15;
        $other_allowance  = $gross * 0.05;

        // Update or Create Salary
        UserSalary::updateOrCreate(
            ['user_id' => $user->id],
            [
                'gross_salary'     => $gross,
                'basic'            => $basic,
                'hra'              => $hra,
                'conveyance'       => $conveyance,
                'simple_allowance' => $simple_allowance,
                'other_allowance'  => $other_allowance,
            ]
        );

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success','User deleted successfully.');
    }
}
