<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Designation;
use App\Models\Department;

class DesignationController extends Controller
{
    public function index(Request $request)
    {
        $query = Designation::query();
        // ✅ Apply name filter if search input present
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $designations = $query->latest()->paginate(10);
        return view('Admin.designations.index', compact('designations'));
    }


    public function create()
    {
        $departments = Department::all();
        return view('Admin.designations.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:designations,name',
            'department_id' => 'required|exists:departments,id',
            'cl' => 'nullable|integer|min:0',
        ]);

        Designation::create($request->only('department_id', 'name', 'cl', 'description'));

        return redirect()->route('admin.designations.index')->with('success', 'Designation created successfully.');
    }

    public function edit(Designation $designation)
    {
        $departments = Department::all();
        return view('Admin.designations.edit', compact('designation', 'departments'));
    }

    public function update(Request $request, Designation $designation)
    {
        $request->validate([
            'name' => 'required|unique:designations,name,' . $designation->id,
            'department_id' => 'required|exists:departments,id',
            'cl' => 'nullable|integer|min:0',
        ]);

        $designation->update($request->only('department_id', 'name', 'cl', 'description'));

        return redirect()->route('admin.designations.index')->with('success', 'Designation updated successfully.');
    }

    public function destroy(Designation $designation)
    {
        $designation->delete();
        return redirect()->route('admin.designations.index')->with('success', 'Designation deleted successfully.');
    }
}

