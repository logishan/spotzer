<?php

namespace App\Http\Controllers;

use App\Models\TaskType;
use App\Models\ComplexityLevel;
use App\Models\TaskTypeComplexity;
use App\Models\TaskGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskTypeComplexityController extends Controller
{
    public function index()
    {
        $complexities = TaskTypeComplexity::with(['taskType', 'complexityLevel', 'taskGroup'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('task-type-complexities.index', compact('complexities'));
    }

    public function create()
    {
        $taskTypes = TaskType::orderBy('name')->get();
        $complexityLevels = ComplexityLevel::orderBy('name')->get();
        $taskGroups = TaskGroup::where('is_active', true)->orderBy('name')->get();

        return view('task-type-complexities.create', compact('taskTypes', 'complexityLevels', 'taskGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_type_id' => 'required|exists:task_types,id',
            'complexity_level_id' => 'required|exists:complexity_levels,id',
            'allocated_minutes' => 'required|integer|min:1',
            'task_group_id' => 'required|exists:task_groups,id',
            'description' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');

        TaskTypeComplexity::create($validated);

        return redirect()->route('task-type-complexities.index')
            ->with('success', 'Task type complexity created successfully.');
    }

    public function edit(TaskTypeComplexity $taskTypeComplexity)
    {
        $taskTypes = TaskType::orderBy('name')->get();
        $complexityLevels = ComplexityLevel::orderBy('name')->get();
        $taskGroups = TaskGroup::where('is_active', true)->orderBy('name')->get();

        return view('task-type-complexities.edit', compact('taskTypeComplexity', 'taskTypes', 'complexityLevels', 'taskGroups'));
    }

    public function update(Request $request, TaskTypeComplexity $taskTypeComplexity)
    {
        $validated = $request->validate([
            'task_type_id' => 'required|exists:task_types,id',
            'complexity_level_id' => 'required|exists:complexity_levels,id',
            'allocated_minutes' => 'required|integer|min:1',
            'task_group_id' => 'required|exists:task_groups,id',
            'description' => 'nullable|string',
        ]);

        $previousMinutes = $taskTypeComplexity->allocated_minutes;
        $validated['updated_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');

        $taskTypeComplexity->update($validated);

        // Record history if allocated minutes changed
        if ($previousMinutes !== $validated['allocated_minutes']) {
            $taskTypeComplexity->recordHistory(
                $previousMinutes,
                $validated['allocated_minutes'],
                $request->input('reason')
            );
        }

        return redirect()->route('task-type-complexities.index')
            ->with('success', 'Task type complexity updated successfully.');
    }

    public function destroy(TaskTypeComplexity $taskTypeComplexity)
    {
        $taskTypeComplexity->delete();

        return redirect()->route('task-type-complexities.index')
            ->with('success', 'Task type complexity deleted successfully.');
    }
} 