<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\TaskGroup;
use App\Models\ShiftSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'departments', 'taskGroups'])->get();
        return view('users.management.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('level')->get();
        $departments = Department::all();
        $taskGroups = TaskGroup::where('is_active', true)->orderBy('name')->get();
        $shiftSchedules = ShiftSchedule::where('is_active', true)->orderBy('name')->get();
        
        return view('users.management.create', compact('roles', 'departments', 'taskGroups', 'shiftSchedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id',
            'department_id' => 'required|exists:departments,id',
            'shift_schedule_id' => 'nullable|exists:shift_schedules,id',
            'task_group_ids' => 'nullable|array',
            'task_group_ids.*' => 'exists:task_groups,id',
            'status' => 'required|in:active,inactive',
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->status = $validated['status'];
        $user->department_id = $validated['department_id'];
        
        // Set the primary role_id from the first selected role
        $user->role_id = $validated['role_ids'][0];
        
        if (!empty($validated['shift_schedule_id'])) {
            $user->shift_schedule_id = $validated['shift_schedule_id'];
        }
        
        $user->save();

        // Assign roles
        $user->roles()->sync($validated['role_ids']);
        
        // Assign department
        $user->departments()->sync([$validated['department_id']]);
        
        // Assign task groups if provided
        if (!empty($validated['task_group_ids'])) {
            $user->taskGroups()->sync($validated['task_group_ids']);
        }

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('level')->get();
        $departments = Department::all();
        $taskGroups = TaskGroup::where('is_active', true)->orderBy('name')->get();
        $shiftSchedules = ShiftSchedule::where('is_active', true)->orderBy('name')->get();
        
        return view('users.management.edit', compact('user', 'roles', 'departments', 'taskGroups', 'shiftSchedules'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id',
            'department_id' => 'required|exists:departments,id',
            'shift_schedule_id' => 'nullable|exists:shift_schedules,id',
            'task_group_ids' => 'nullable|array',
            'task_group_ids.*' => 'exists:task_groups,id',
            'password' => 'nullable|min:8|confirmed',
            'status' => 'required|in:active,inactive',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->status = $validated['status'];
        $user->department_id = $validated['department_id'];
        $user->shift_schedule_id = $validated['shift_schedule_id'] ?? null;
        
        // Update the primary role_id from the first selected role
        $user->role_id = $validated['role_ids'][0];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Update roles
        $user->roles()->sync($validated['role_ids']);
        
        // Update department
        $user->departments()->sync([$validated['department_id']]);
        
        // Update task groups
        $user->taskGroups()->sync($validated['task_group_ids'] ?? []);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting your own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
} 