<?php

namespace App\Http\Controllers;

use App\Models\ComplexityLevel;
use App\Models\TaskGroup;
use Illuminate\Http\Request;

class ComplexityLevelController extends Controller
{
    public function index()
    {
        $complexityLevels = ComplexityLevel::with('taskGroup')
            ->withCount('taskTypeComplexities')
            ->orderBy('name')
            ->get();
        
        return view('task-complexity.index', compact('complexityLevels'));
    }

    public function create()
    {
        $taskGroups = TaskGroup::where('is_active', true)->orderBy('name')->get();
        return view('task-complexity.create', compact('taskGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:complexity_levels,name',
            'task_group_id' => 'required|exists:task_groups,id'
        ]);

        ComplexityLevel::create($validated);

        return redirect()->route('task-complexity.index')
            ->with('success', 'Complexity level created successfully.');
    }

    public function edit(ComplexityLevel $task_complexity)
    {
        $taskGroups = TaskGroup::where('is_active', true)->orderBy('name')->get();
        return view('task-complexity.edit', compact('task_complexity', 'taskGroups'));
    }

    public function update(Request $request, ComplexityLevel $task_complexity)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:complexity_levels,name,' . $task_complexity->id,
            'task_group_id' => 'required|exists:task_groups,id'
        ]);

        $task_complexity->update($validated);

        return redirect()->route('task-complexity.index')
            ->with('success', 'Complexity level updated successfully.');
    }

    public function destroy(ComplexityLevel $task_complexity)
    {
        if ($task_complexity->taskTypeComplexities()->exists()) {
            return redirect()->route('task-complexity.index')
                ->with('error', 'Cannot delete complexity level that is in use.');
        }

        $task_complexity->delete();

        return redirect()->route('task-complexity.index')
            ->with('success', 'Complexity level deleted successfully.');
    }
} 