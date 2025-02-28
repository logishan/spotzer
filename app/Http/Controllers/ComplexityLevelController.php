<?php

namespace App\Http\Controllers;

use App\Models\ComplexityLevel;
use Illuminate\Http\Request;

class ComplexityLevelController extends Controller
{
    public function index()
    {
        $complexityLevels = ComplexityLevel::withCount('taskTypeComplexities')
            ->orderBy('name')
            ->get();
        
        return view('complexity-levels.index', compact('complexityLevels'));
    }

    public function create()
    {
        return view('complexity-levels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:complexity_levels,name',
            'description' => 'nullable|string'
        ]);

        ComplexityLevel::create($validated);

        return redirect()->route('complexity-levels.index')
            ->with('success', 'Complexity level created successfully.');
    }

    public function edit(ComplexityLevel $complexityLevel)
    {
        return view('complexity-levels.edit', compact('complexityLevel'));
    }

    public function update(Request $request, ComplexityLevel $complexityLevel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:complexity_levels,name,' . $complexityLevel->id,
            'description' => 'nullable|string'
        ]);

        $complexityLevel->update($validated);

        return redirect()->route('complexity-levels.index')
            ->with('success', 'Complexity level updated successfully.');
    }

    public function destroy(ComplexityLevel $complexityLevel)
    {
        if ($complexityLevel->taskTypeComplexities()->exists()) {
            return redirect()->route('complexity-levels.index')
                ->with('error', 'Cannot delete complexity level that is in use.');
        }

        $complexityLevel->delete();

        return redirect()->route('complexity-levels.index')
            ->with('success', 'Complexity level deleted successfully.');
    }
} 