<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    // Show all departments
    public function index(Request $request)
    {   
        // dd($request->all());
        $query = Department::query();
        // ✅ Apply name filter if search input present
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        $departments = $query->latest()->paginate(10);

        return view('Admin.departments.index', compact('departments'));
    }

    // Show create form
    public function create()
    {
        return view('Admin.departments.create');
    }

    // Store new department
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:departments,name',
        ]);

        Department::create($request->only('name'));

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully.');
    }

    // Show edit form
    public function edit(Department $department)
    {
        return view('Admin.departments.edit', compact('department'));
    }

    // Update department
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|unique:departments,name,' . $department->id,
        ]);

        $department->update($request->only('name'));

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully.');
    }

    // Delete department
    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully.');
    }
}
