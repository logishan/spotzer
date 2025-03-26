<?php

namespace App\Http\Controllers;

use App\Models\ShiftSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShiftScheduleController extends Controller
{
    /**
     * Display a listing of shift schedules
     */
    public function index()
    {
        $schedules = ShiftSchedule::latest()->get();
        return view('shift-schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new shift schedule
     */
    public function create()
    {
        return view('shift-schedules.create');
    }

    /**
     * Store a newly created shift schedule
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shift_schedules,name',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'integer|between:0,6',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        if (empty($validated['days_of_week'])) {
            $validated['days_of_week'] = [];
        }

        $shiftSchedule = ShiftSchedule::create($validated);

        return redirect()->route('shift-schedules.index')
            ->with('success', 'Shift schedule created successfully.');
    }

    /**
     * Display the specified shift schedule with assigned users
     */
    public function show(ShiftSchedule $shiftSchedule)
    {
        $users = $shiftSchedule->users;
        return view('shift-schedules.show', compact('shiftSchedule', 'users'));
    }

    /**
     * Show the form for editing the specified shift schedule
     */
    public function edit(ShiftSchedule $shiftSchedule)
    {
        return view('shift-schedules.edit', compact('shiftSchedule'));
    }

    /**
     * Update the specified shift schedule
     */
    public function update(Request $request, ShiftSchedule $shiftSchedule)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('shift_schedules')->ignore($shiftSchedule->id)],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'integer|between:0,6',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        if (empty($validated['days_of_week'])) {
            $validated['days_of_week'] = [];
        }

        $shiftSchedule->update($validated);

        return redirect()->route('shift-schedules.index')
            ->with('success', 'Shift schedule updated successfully.');
    }

    /**
     * Remove the specified shift schedule
     */
    public function destroy(ShiftSchedule $shiftSchedule)
    {
        // Check if there are users associated with this schedule
        if ($shiftSchedule->users()->count() > 0) {
            return redirect()->route('shift-schedules.index')
                ->with('error', 'Cannot delete shift schedule with assigned users.');
        }

        $shiftSchedule->delete();

        return redirect()->route('shift-schedules.index')
            ->with('success', 'Shift schedule deleted successfully.');
    }
}
