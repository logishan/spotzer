<?php

namespace App\Http\Controllers;

use App\Models\TaskType;
use App\Models\ComplexityLevel;
use App\Models\TaskTypeComplexity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskTypeComplexityController extends Controller
{
    public function index()
    {
        $taskTypeComplexities = TaskTypeComplexity::with(['taskType', 'complexityLevel'])
            ->orderBy('effective_from', 'desc')
            ->get();
        
        return view('task-type-complexities.index', compact('taskTypeComplexities'));
    }

    public function create()
    {
        $taskTypes = TaskType::where('is_active', true)->orderBy('name')->get();
        $complexityLevels = ComplexityLevel::orderBy('name')->get();
        
        return view('task-type-complexities.create', compact('taskTypes', 'complexityLevels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_type_id' => 'required|exists:task_types,id',
            'complexity_level_id' => 'required|exists:complexity_levels,id',
            'allocated_minutes' => 'required|integer|min:1',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after:effective_from'
        ]);

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        // Check for overlapping periods
        $exists = TaskTypeComplexity::where('task_type_id', $validated['task_type_id'])
            ->where('complexity_level_id', $validated['complexity_level_id'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('effective_from', [$validated['effective_from'], $validated['effective_to'] ?? '9999-12-31'])
                    ->orWhereBetween('effective_to', [$validated['effective_from'], $validated['effective_to'] ?? '9999-12-31'])
                    ->orWhereNull('effective_to');
            })
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'An overlapping period exists for this task type and complexity level.']);
        }

        TaskTypeComplexity::create($validated);

        return redirect()->route('task-type-complexities.index')
            ->with('success', 'Task type complexity created successfully.');
    }

    public function edit(TaskTypeComplexity $taskTypeComplexity)
    {
        $taskTypes = TaskType::where('is_active', true)->orderBy('name')->get();
        $complexityLevels = ComplexityLevel::orderBy('name')->get();
        
        return view('task-type-complexities.edit', compact('taskTypeComplexity', 'taskTypes', 'complexityLevels'));
    }

    public function update(Request $request, TaskTypeComplexity $taskTypeComplexity)
    {
        $validated = $request->validate([
            'task_type_id' => 'required|exists:task_types,id',
            'complexity_level_id' => 'required|exists:complexity_levels,id',
            'allocated_minutes' => 'required|integer|min:1',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after:effective_from'
        ]);

        $validated['updated_by'] = Auth::id();

        // Check for overlapping periods
        $exists = TaskTypeComplexity::where('task_type_id', $validated['task_type_id'])
            ->where('complexity_level_id', $validated['complexity_level_id'])
            ->where('id', '!=', $taskTypeComplexity->id)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('effective_from', [$validated['effective_from'], $validated['effective_to'] ?? '9999-12-31'])
                    ->orWhereBetween('effective_to', [$validated['effective_from'], $validated['effective_to'] ?? '9999-12-31'])
                    ->orWhereNull('effective_to');
            })
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'An overlapping period exists for this task type and complexity level.']);
        }

        // Record history if allocated minutes changed
        if ($taskTypeComplexity->allocated_minutes != $validated['allocated_minutes']) {
            $taskTypeComplexity->recordHistory(
                $taskTypeComplexity->allocated_minutes,
                $validated['allocated_minutes'],
                $request->input('change_reason')
            );
        }

        $taskTypeComplexity->update($validated);

        return redirect()->route('task-type-complexities.index')
            ->with('success', 'Task type complexity updated successfully.');
    }

    public function destroy(TaskTypeComplexity $taskTypeComplexity)
    {
        if ($taskTypeComplexity->tasks()->exists()) {
            return redirect()->route('task-type-complexities.index')
                ->with('error', 'Cannot delete task type complexity that has tasks assigned.');
        }

        $taskTypeComplexity->delete();

        return redirect()->route('task-type-complexities.index')
            ->with('success', 'Task type complexity deleted successfully.');
    }
} 