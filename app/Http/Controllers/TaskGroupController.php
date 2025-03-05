<?php

namespace App\Http\Controllers;

use App\Models\TaskGroup;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskGroupController extends Controller
{
    public function index()
    {
        $taskGroups = TaskGroup::with(['creator', 'departments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('task-groups.index', compact('taskGroups'));
    }

    public function create()
    {
        $departments = Department::all();
        $users = User::where('is_active', true)->get();
        
        return view('task-groups.create', compact('departments', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'departments' => 'required|array',
            'departments.*' => 'exists:departments,id',
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);

        $taskGroup = TaskGroup::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'is_active' => $validated['is_active'] ?? true,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        // Sync departments
        $taskGroup->departments()->sync($validated['departments']);

        // Sync users with default permissions
        $userPermissions = collect($validated['users'])->mapWithKeys(function ($userId) {
            return [$userId => [
                'can_view' => true,
                'can_edit' => false,
                'granted_by' => Auth::id()
            ]];
        });
        $taskGroup->users()->sync($userPermissions);

        return redirect()->route('task-groups.index')
            ->with('success', 'Task group created successfully.');
    }

    public function show(TaskGroup $taskGroup)
    {
        $taskGroup->load(['creator', 'departments', 'users', 'tasks']);
        return view('task-groups.show', compact('taskGroup'));
    }

    public function edit(TaskGroup $taskGroup)
    {
        $departments = Department::all();
        $users = User::where('is_active', true)->get();
        $taskGroup->load(['departments', 'users']);
        
        return view('task-groups.edit', compact('taskGroup', 'departments', 'users'));
    }

    public function update(Request $request, TaskGroup $taskGroup)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'departments' => 'required|array',
            'departments.*' => 'exists:departments,id',
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);

        $taskGroup->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'is_active' => $validated['is_active'] ?? true,
            'updated_by' => Auth::id(),
        ]);

        // Sync departments
        $taskGroup->departments()->sync($validated['departments']);

        // Sync users while preserving existing permissions
        $existingPermissions = $taskGroup->users()->pluck('task_group_access.can_edit', 'users.id');
        $userPermissions = collect($validated['users'])->mapWithKeys(function ($userId) use ($existingPermissions) {
            return [$userId => [
                'can_view' => true,
                'can_edit' => $existingPermissions[$userId] ?? false,
                'granted_by' => Auth::id()
            ]];
        });
        $taskGroup->users()->sync($userPermissions);

        return redirect()->route('task-groups.show', $taskGroup)
            ->with('success', 'Task group updated successfully.');
    }

    public function destroy(TaskGroup $taskGroup)
    {
        if ($taskGroup->tasks()->exists()) {
            return redirect()->route('task-groups.index')
                ->with('error', 'Cannot delete task group that has tasks assigned.');
        }

        $taskGroup->delete();
        return redirect()->route('task-groups.index')
            ->with('success', 'Task group deleted successfully.');
    }

    public function updateUserPermissions(Request $request, TaskGroup $taskGroup, User $user)
    {
        $validated = $request->validate([
            'can_edit' => 'required|boolean'
        ]);

        $taskGroup->users()->updateExistingPivot($user->id, [
            'can_edit' => $validated['can_edit'],
            'granted_by' => Auth::id()
        ]);

        return response()->json(['message' => 'Permissions updated successfully']);
    }
} 