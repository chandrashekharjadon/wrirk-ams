<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\User;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = Leave::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('date', $request->month)
                ->whereYear('date', $request->year);
        }

        $leaves = $query->latest()->paginate(10);
        $users = User::all();

        return view('Admin.leaves.index', compact('leaves','users'));
    }

    public function create()
    {
        $users = User::all();
        return view('Admin.leaves.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'reason' => 'nullable|string',
        ]);

        Leave::create($request->only('user_id','date','reason'));

        return redirect()->route('admin.leaves.index')->with('success','Leave created successfully.');
    }

    public function edit(Leave $leave)
    {
        $users = User::all();
        return view('Admin.leaves.edit', compact('leave','users'));
    }

    public function update(Request $request, Leave $leave)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'reason' => 'nullable|string',
        ]);

        $leave->update($request->only('user_id','date','reason'));

        return redirect()->route('admin.leaves.index')->with('success','Leave updated successfully.');
    }

    public function destroy(Leave $leave)
    {
        $leave->delete();
        return redirect()->route('admin.leaves.index')->with('success','Leave deleted successfully.');
    }
}

