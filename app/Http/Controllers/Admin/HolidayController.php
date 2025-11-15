<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HolidayController extends Controller
{
    /**
     * Display all holidays.
     */
    public function index(Request $request)
    {
        // Start base query
        $query = Holiday::query();
        
        // Apply Month & Year filters if provided
        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('date', $request->month)
                ->whereYear('date', $request->year);
        }

        // Order and paginate
        $holidays = $query->latest()->paginate(10);
        return view('Admin.holidays.index', compact('holidays'));
    }

    /**
     * Show form for creating a new holiday.
     */
    public function create()
    {
        $types = ['public', 'restricted']; // available holiday types
        $users = User::orderBy('name')->get();

        return view('Admin.holidays.create', compact('types', 'users'));
    }

    /**
     * Store a newly created holiday in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:holidays,name',
            'date'        => 'required|date|unique:holidays,date',
            'type'        => 'required|in:public,restricted',
            'description' => 'nullable|string',
            'users'       => 'nullable|array',
        ], [
            'name.unique' => 'A holiday with this name already exists.',
            'date.unique' => 'A holiday already exists on this date.',
        ]);

        DB::beginTransaction();
        try {
            // Create holiday
            $holiday = Holiday::create($request->only('name', 'date', 'type', 'description'));

            // If restricted, attach selected users
            if ($request->type === 'restricted' && !empty($request->users)) {
                $holiday->users()->attach($request->users);
            }

            DB::commit();
            return redirect()->route('admin.holidays.index')
                            ->with('success', 'Holiday created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show form for editing a holiday.
     */
    public function edit(Holiday $holiday)
    {
        $types = ['public', 'restricted'];
        $users = User::orderBy('name')->get();
        $holiday->load('users'); // eager load assigned users

        // Collect selected user IDs for preselecting in the form
        $selectedUsers = $holiday->users->pluck('id')->toArray();

        return view('Admin.holidays.edit', compact('holiday', 'types', 'users', 'selectedUsers'));
    }

    /**
     * Update holiday details.
     */
    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'date'        => 'required|date',
            'type'        => 'required|in:public,restricted',
            'description' => 'nullable|string',
            'users'       => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            // Update holiday
            $holiday->update($request->only('name', 'date', 'type', 'description'));

            // Sync users based on type
            if ($request->type === 'restricted' && !empty($request->users)) {
                $holiday->users()->sync($request->users);
            } else {
                // Remove all users if public
                $holiday->users()->detach();
            }

            DB::commit();
            return redirect()->route('admin.holidays.index')->with('success', 'Holiday updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete holiday.
     */
    public function destroy(Holiday $holiday)
    {
        DB::beginTransaction();
        try {
            $holiday->users()->detach(); // remove pivot relations
            $holiday->delete();
            DB::commit();

            return redirect()->route('admin.holidays.index')->with('success', 'Holiday deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }
}
