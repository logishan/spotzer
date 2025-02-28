<?php

namespace App\Http\Controllers;

use App\Models\TaskType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $taskTypes = TaskType::with('complexities.complexityLevel')
            ->withCount('complexities')
            ->get();
        
        return view('task-types.index', compact('taskTypes'));
    }

    public function create()
    {
        return view('task-types.create');
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'You must be logged in to create a task type.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:task_types,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        TaskType::create($validated);

        return redirect()->route('task-types.index')
            ->with('success', 'Task type created successfully.');
    }

    public function edit(TaskType $taskType)
    {
        return view('task-types.edit', compact('taskType'));
    }

    public function update(Request $request, TaskType $taskType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:task_types,name,' . $taskType->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['updated_by'] = Auth::id();

        $taskType->update($validated);

        return redirect()->route('task-types.index')
            ->with('success', 'Task type updated successfully.');
    }

    public function destroy(TaskType $taskType)
    {
        if ($taskType->complexities()->exists()) {
            return redirect()->route('task-types.index')
                ->with('error', 'Cannot delete task type that has complexity levels assigned.');
        }

        $taskType->delete();

        return redirect()->route('task-types.index')
            ->with('success', 'Task type deleted successfully.');
    }
} 