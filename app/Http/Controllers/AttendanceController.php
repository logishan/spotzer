<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display a listing of attendances
     */
    public function index()
    {
        $request = request();
        $query = Attendance::with('user');
        
        // Date filtering
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        // User filtering
        $userId = $request->input('user_id');
        
        // Status filtering
        $status = $request->input('status');
        
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $attendances = $query->orderBy('date', 'desc')->paginate(15);
        $users = User::where('is_active', true)->get();
        
        return view('attendances.index', compact('attendances', 'users', 'startDate', 'endDate', 'userId', 'status'));
    }
    
    /**
     * Display the attendance sheet for all users
     */
    public function sheet()
    {
        $request = request();
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $departmentId = $request->input('department_id');
        
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();
        $daysInMonth = $startDate->daysInMonth;
        
        $usersQuery = User::where('is_active', true);
        
        if ($departmentId) {
            $usersQuery->where('department_id', $departmentId);
        }
        
        $users = $usersQuery->with(['attendances' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        }, 'shiftSchedule'])->get();
        
        $departments = \App\Models\Department::all();
        
        return view('attendances.sheet', compact('users', 'month', 'daysInMonth', 'startDate', 'departments', 'departmentId'));
    }
    
    /**
     * Show the form for creating a new attendance record
     */
    public function create()
    {
        $users = User::where('is_active', true)->get();
        return view('attendances.create', compact('users'));
    }
    
    /**
     * Store a newly created attendance record
     */
    public function store()
    {
        $request = request();
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,half_day,leave',
            'notes' => 'nullable|string',
        ]);
        
        // Check for existing record
        $exists = Attendance::where('user_id', $validated['user_id'])
                           ->where('date', $validated['date'])
                           ->exists();
        
        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Attendance record already exists for this user on this date.');
        }
        
        Attendance::create($validated);
        
        return redirect()->route('attendances.index')
            ->with('success', 'Attendance record created successfully.');
    }
    
    /**
     * Check in for current user
     */
    public function checkIn()
    {
        $user = Auth::user();
        $today = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now()->format('H:i');
        
        // Check if already checked in today
        $attendance = Attendance::where('user_id', $user->id)
                               ->where('date', $today)
                               ->first();
        
        if ($attendance) {
            if ($attendance->time_in) {
                return redirect()->back()
                    ->with('error', 'You have already checked in today.');
            }
            
            $attendance->update([
                'time_in' => $currentTime,
                'status' => 'present'
            ]);
        } else {
            Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'time_in' => $currentTime,
                'status' => 'present'
            ]);
        }
        
        return redirect()->back()
            ->with('success', 'Check-in successful at ' . $currentTime);
    }
    
    /**
     * Check out for current user
     */
    public function checkOut()
    {
        $user = Auth::user();
        $today = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now()->format('H:i');
        
        // Find today's attendance record
        $attendance = Attendance::where('user_id', $user->id)
                               ->where('date', $today)
                               ->first();
        
        if (!$attendance) {
            // Create a new record if none exists
            Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'time_out' => $currentTime,
                'status' => 'present'
            ]);
            
            return redirect()->back()
                ->with('success', 'Check-out successful at ' . $currentTime);
        }
        
        if ($attendance->time_out) {
            return redirect()->back()
                ->with('error', 'You have already checked out today.');
        }
        
        $attendance->update([
            'time_out' => $currentTime
        ]);
        
        return redirect()->back()
            ->with('success', 'Check-out successful at ' . $currentTime);
    }
    
    /**
     * Show the form for editing the specified attendance record
     */
    public function edit(Attendance $attendance)
    {
        $users = User::where('is_active', true)->get();
        return view('attendances.edit', compact('attendance', 'users'));
    }
    
    /**
     * Update the specified attendance record
     */
    public function update(Attendance $attendance)
    {
        $request = request();
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,half_day,leave',
            'notes' => 'nullable|string',
        ]);
        
        // Check for existing record (excluding current one)
        $exists = Attendance::where('user_id', $validated['user_id'])
                           ->where('date', $validated['date'])
                           ->where('id', '!=', $attendance->id)
                           ->exists();
        
        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Another attendance record already exists for this user on this date.');
        }
        
        $attendance->update($validated);
        
        return redirect()->route('attendances.index')
            ->with('success', 'Attendance record updated successfully.');
    }
    
    /**
     * Remove the specified attendance record
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        
        return redirect()->route('attendances.index')
            ->with('success', 'Attendance record deleted successfully.');
    }
    
    /**
     * Display the specified attendance record
     */
    public function show(Attendance $attendance)
    {
        return view('attendances.show', compact('attendance'));
    }
}
