<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wfh;
use App\Models\User;

class WfhController extends Controller
{
    public function index(Request $request)
    {
        $query = Wfh::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('date', $request->month)
                ->whereYear('date', $request->year);
        }

        $wfhRecords = $query->latest()->paginate(10);
        $users = User::all();

        return view('Admin.wfhs.index', compact('wfhRecords', 'users'));
    }


    public function create()
    {
        $users = User::all();
        return view('Admin.wfhs.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'percent' => 'required|numeric|min:0|max:100',
            'check_in' => 'required|date_format:H:i',
            'check_out' => 'required|date_format:H:i|after:check_in',
        ]);

        Wfh::create($request->only('user_id','date','percent','check_in','check_out'));

        return redirect()->route('admin.wfhs.index')->with('success','WFH record created successfully.');
    }

    public function edit(Wfh $wfh)
    {
        $users = User::all();
        return view('Admin.wfhs.edit', compact('wfh','users'));
    }

    public function update(Request $request, Wfh $wfh)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'percent' => 'required|numeric|min:0|max:100',
            'check_in' => 'required|date_format:H:i',
            'check_out' => 'required|date_format:H:i|after:check_in',
        ]);

        $wfh->update($request->only('user_id','date','percent','check_in','check_out'));

        return redirect()->route('admin.wfhs.index')->with('success','WFH record updated successfully.');
    }

    public function destroy(Wfh $wfh)
    {
        $wfh->delete();
        return redirect()->route('admin.wfhs.index')->with('success','WFH record deleted successfully.');
    }
}

