<?php

namespace App\Http\Controllers;

use App\Models\FormField;
use App\Models\TaskGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FormFieldController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $request = request();
        $taskGroups = TaskGroup::where('is_active', true)->orderBy('name')->get();
        $selectedTaskGroup = null;
        $formFields = collect();

        if ($request->has('task_group_id')) {
            $selectedTaskGroup = TaskGroup::findOrFail($request->task_group_id);
            $formFields = FormField::where('task_group_id', $selectedTaskGroup->id)
                ->orderBy('display_order')
                ->get();
        }

        return view('form-fields.index', compact('taskGroups', 'selectedTaskGroup', 'formFields'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $request = request();
        
        if (!$request->has('task_group_id')) {
            return redirect()->route('form-fields.index')
                ->with('error', 'Please select a task group first.');
        }

        $taskGroup = TaskGroup::findOrFail($request->task_group_id);
        $fieldTypes = [
            'text' => 'Text Input',
            'textarea' => 'Text Area',
            'dropdown' => 'Dropdown',
            'radio' => 'Radio Buttons',
            'checkbox' => 'Checkbox',
            'date' => 'Date Picker',
        ];

        return view('form-fields.create', compact('taskGroup', 'fieldTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_group_id' => 'required|exists:task_groups,id',
            'name' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'type' => 'required|in:text,textarea,dropdown,radio,checkbox,date',
            'required' => 'boolean',
            'display_order' => 'integer|min:0',
            'options' => 'nullable|array',
            'validation_rules' => 'nullable|array',
        ]);

        // Format options and validation rules
        $options = null;
        if ($request->has('options') && is_array($request->options)) {
            $options = [];
            foreach ($request->options as $key => $value) {
                if (!empty($key) && !empty($value)) {
                    $options[$key] = $value;
                }
            }
        }

        $validationRules = null;
        if ($request->has('validation_rules') && is_array($request->validation_rules)) {
            $validationRules = array_filter($request->validation_rules);
        }

        // Create form field
        $formField = FormField::create([
            'task_group_id' => $validated['task_group_id'],
            'name' => $validated['name'],
            'label' => $validated['label'],
            'type' => $validated['type'],
            'required' => $request->has('required'),
            'display_order' => $validated['display_order'] ?? 0,
            'options' => $options,
            'validation_rules' => $validationRules,
            'is_active' => true,
        ]);

        return redirect()->route('form-fields.index', ['task_group_id' => $validated['task_group_id']])
            ->with('success', 'Form field created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $formField = FormField::findOrFail($id);
        $taskGroup = $formField->taskGroup;
        $fieldTypes = [
            'text' => 'Text Input',
            'textarea' => 'Text Area',
            'dropdown' => 'Dropdown',
            'radio' => 'Radio Buttons',
            'checkbox' => 'Checkbox',
            'date' => 'Date Picker',
        ];

        return view('form-fields.edit', compact('formField', 'taskGroup', 'fieldTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $formField = FormField::findOrFail($id);

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|in:text,textarea,dropdown,radio,checkbox,date',
            'required' => 'boolean',
            'display_order' => 'integer|min:0',
            'options' => 'nullable|array',
            'validation_rules' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        // Format options and validation rules
        $options = null;
        if ($request->has('options') && is_array($request->options)) {
            $options = [];
            foreach ($request->options as $key => $value) {
                if (!empty($key) && !empty($value)) {
                    $options[$key] = $value;
                }
            }
        }

        $validationRules = null;
        if ($request->has('validation_rules') && is_array($request->validation_rules)) {
            $validationRules = array_filter($request->validation_rules);
        }

        // Update form field
        $formField->update([
            'label' => $validated['label'],
            'type' => $validated['type'],
            'required' => $request->has('required'),
            'display_order' => $validated['display_order'] ?? 0,
            'options' => $options,
            'validation_rules' => $validationRules,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('form-fields.index', ['task_group_id' => $formField->task_group_id])
            ->with('success', 'Form field updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $formField = FormField::findOrFail($id);
        $taskGroupId = $formField->task_group_id;
        
        $formField->delete();

        return redirect()->route('form-fields.index', ['task_group_id' => $taskGroupId])
            ->with('success', 'Form field deleted successfully.');
    }
}
