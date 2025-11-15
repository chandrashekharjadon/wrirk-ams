<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CasualLeaveBalance;
use App\Models\User;

class CasualLeaveBalanceController extends Controller
{
    public function index(Request $request)
    {
        $query = CasualLeaveBalance::with('user');
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $balances = $query->latest()->paginate(10);
        $users = User::all();
        
        return view('Admin.casual_leave_balances.index', compact('balances','users'));
    }

    public function create()
    {
        $users = User::all();
        return view('Admin.casual_leave_balances.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'   => 'required|exists:users,id|unique:casual_leave_balances,user_id',
            'total'     => 'required|integer|min:0',
            'used'      => 'required|integer|min:0',
            'remaining' => 'required|integer|min:0',
            'year'      => 'required|digits:4|integer',
        ], [
            'user_id.unique' => 'This user already has a record.',
        ]);

        CasualLeaveBalance::create($request->all());

        return redirect()->route('admin.casual_leave_balances.index')->with('success','Casual leave balance created.');
    }

    public function edit(CasualLeaveBalance $casual_leave_balance)
    {
        $users = User::all();
        return view('Admin.casual_leave_balances.edit', compact('casual_leave_balance','users'));
    }

    public function update(Request $request, CasualLeaveBalance $casual_leave_balance)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'total' => 'required|integer|min:0',
            'used' => 'required|integer|min:0',
            'remaining' => 'required|integer|min:0',
            'year' => 'required|digits:4|integer',
        ]);

        $casual_leave_balance->update($request->all());

        return redirect()->route('admin.casual_leave_balances.index')->with('success','Casual leave balance updated.');
    }

    public function destroy(CasualLeaveBalance $casual_leave_balance)
    {
        $casual_leave_balance->delete();
        return redirect()->route('admin.casual_leave_balances.index')->with('success','Casual leave balance deleted.');
    }
}
