<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskGroup;
use App\Models\TaskType;
use App\Models\TaskTypeComplexity;
use App\Models\ComplexityLevel;
use App\Models\FormField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of tasks.
     */
    public function index()
    {
        $tasks = Task::with(['taskGroups', 'taskTypeComplexity.taskType', 'taskTypeComplexity.complexityLevel'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Get task list for kanban board
     */
    public function kanban(TaskGroup $taskGroup = null)
    {
        $query = Task::with(['taskGroups', 'taskTypeComplexity.taskType', 'taskTypeComplexity.complexityLevel', 'creator']);
        
        if ($taskGroup) {
            $query->whereHas('taskGroups', function($query) use ($taskGroup) {
                $query->where('task_groups.id', $taskGroup->id);
            });
        }
        
        $tasks = $query->get()->groupBy('status');
        
        // Ensure all status columns exist even if empty
        $statuses = ['todo', 'in_progress', 'waiting', 'paused', 'completed'];
        foreach ($statuses as $status) {
            if (!isset($tasks[$status])) {
                $tasks[$status] = collect();
            }
        }
        
        $taskGroups = TaskGroup::where('is_active', true)->orderBy('name')->get();
        
        return view('tasks.kanban', compact('tasks', 'taskGroups', 'taskGroup'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        $request = request();
        $taskGroups = TaskGroup::where('is_active', true)->orderBy('name')->get();
        $selectedTaskGroup = null;
        $formFields = [];
        
        // Debug the request parameters
        \Log::info('Task create request', [
            'has_task_group_id' => $request->has('task_group_id'),
            'task_group_id' => $request->task_group_id,
            'all_params' => $request->all()
        ]);
        
        // If task group is specified, load its form fields
        if ($request->has('task_group_id') && !empty($request->task_group_id)) {
            try {
                $selectedTaskGroup = TaskGroup::findOrFail($request->task_group_id);
                \Log::info('Selected task group', ['id' => $selectedTaskGroup->id, 'name' => $selectedTaskGroup->name]);
                
                $formFields = $this->getFormFieldsForTaskGroup($selectedTaskGroup);
                \Log::info('Form fields loaded', ['count' => count($formFields)]);
            } catch (\Exception $e) {
                \Log::error('Error loading task group', ['error' => $e->getMessage()]);
                return redirect()->route('tasks.create')
                    ->with('error', 'Error loading task group: ' . $e->getMessage());
            }
        }
        
        return view('tasks.create', compact('taskGroups', 'selectedTaskGroup', 'formFields'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $taskGroup = TaskGroup::findOrFail($request->task_group_id);
        
        // Basic validation common to all task groups
        $baseRules = [
            'task_group_id' => 'required|exists:task_groups,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,waiting,paused,completed',
            'priority' => 'required|in:low,medium,high,urgent',
        ];
        
        // If we have separate task_type and complexity fields
        if ($request->has('task_type') && $request->has('complexity')) {
            $baseRules['task_type'] = 'required|exists:task_types,id';
            $baseRules['complexity'] = 'required|exists:complexity_levels,id';
        } else {
            // Fall back to combined field
            $baseRules['task_type_complexity_id'] = 'required|exists:task_type_complexities,id';
        }
        
        // Add dynamic field validation based on task group
        $dynamicRules = $this->getDynamicValidationRules($taskGroup);
        $rules = array_merge($baseRules, $dynamicRules);
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Determine task_type_complexity_id
        $taskTypeComplexityId = null;
        
        if ($request->has('task_type_complexity_id')) {
            // If we have the combined field value, use it directly
            $taskTypeComplexityId = $request->task_type_complexity_id;
        } else if ($request->has('task_type') && $request->has('complexity')) {
            // Look up the matching task_type_complexity_id from the task_type and complexity
            $taskTypeComplexity = TaskTypeComplexity::where('task_type_id', $request->task_type)
                ->where('complexity_level_id', $request->complexity)
                ->where('task_group_id', $taskGroup->id)
                ->where('is_active', true)
                ->first();
                
            if ($taskTypeComplexity) {
                $taskTypeComplexityId = $taskTypeComplexity->id;
            } else {
                // If no matching combination exists, we need to handle this error
                return redirect()->back()
                    ->withErrors(['task_type' => 'The selected Task Type and Complexity combination is not valid.'])
                    ->withInput();
            }
        }
        
        // Get the task type complexity
        $taskTypeComplexity = TaskTypeComplexity::findOrFail($taskTypeComplexityId);
        
        // Create the task
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'task_type_complexity_id' => $taskTypeComplexity->id,
            'status' => $request->status,
            'priority' => $request->priority,
            'created_by' => Auth::id(),
        ]);
        
        // Associate with task group
        $task->taskGroups()->attach($request->task_group_id);
        
        // Store dynamic field data
        $dynamicFields = $this->getDynamicFieldData($request, $taskGroup);
        if (!empty($dynamicFields)) {
            // Store in task metadata or related models as appropriate
            // This would depend on your database structure
        }
        
        return redirect()->route('tasks.kanban', ['taskGroup' => $request->task_group_id])
            ->with('success', 'Task created successfully.');
    }

    /**
     * Update task status via AJAX
     */
    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:todo,in_progress,waiting,paused,completed',
        ]);
        
        // Check if moving to in-progress when another task is already there
        if ($request->status === 'in_progress') {
            // Get task group IDs for this task
            $taskGroupIds = $task->taskGroups->pluck('id')->toArray();
            
            // Find any in-progress tasks in the same groups
            $existingInProgressTask = Task::where('status', 'in_progress')
                ->where('id', '!=', $task->id)
                ->whereHas('taskGroups', function($query) use ($taskGroupIds) {
                    $query->whereIn('task_groups.id', $taskGroupIds);
                })
                ->first();
            
            // If found, move it to paused
            if ($existingInProgressTask) {
                $existingInProgressTask->update(['status' => 'paused']);
            }
        }
        
        $task->update(['status' => $request->status]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Get dynamic form fields for a task group
     */
    private function getFormFieldsForTaskGroup(TaskGroup $taskGroup)
    {
        // Log the task group we're getting fields for
        \Log::info('Getting form fields for task group', [
            'id' => $taskGroup->id,
            'name' => $taskGroup->name
        ]);
        
        // Get form fields from database
        $dbFields = $taskGroup->formFields()->where('is_active', true)->get();
        \Log::info('Database form fields', ['count' => $dbFields->count()]);
        
        // Get all available task types that apply to this group
        $availableTaskTypes = TaskType::where(function($query) use ($taskGroup) {
            $query->where('task_group_id', $taskGroup->id)
                ->orWhereNull('task_group_id');
        })->pluck('name', 'id')->toArray();
        \Log::info('Available task types', ['count' => count($availableTaskTypes), 'types' => $availableTaskTypes]);
        
        // Get all available complexity levels that apply to this group
        $availableComplexities = ComplexityLevel::where(function($query) use ($taskGroup) {
            $query->where('task_group_id', $taskGroup->id)
                ->orWhereNull('task_group_id');
        })->pluck('name', 'id')->toArray();
        \Log::info('Available complexities', ['count' => count($availableComplexities), 'complexities' => $availableComplexities]);
        
        if ($dbFields->isNotEmpty()) {
            \Log::info('Using database fields');
            // Return fields from database
            $fields = $dbFields->map(function($field) use ($taskGroup, $availableTaskTypes, $availableComplexities) {
                $options = $field->getFormattedOptions();
                
                // Override with our available options if needed
                if ($field->name === 'task_type' && empty($options)) {
                    $options = $availableTaskTypes;
                }
                
                if ($field->name === 'complexity' && empty($options)) {
                    $options = $availableComplexities;
                }
                
                return [
                    'name' => $field->name,
                    'type' => $field->type,
                    'required' => $field->required,
                    'label' => $field->label,
                    'options' => $options,
                ];
            })->toArray();
            
            \Log::info('Returning fields from database', ['count' => count($fields)]);
            return $fields;
        }
        
        // Fallback to hardcoded fields if nothing in database yet
        \Log::info('No database fields found, using hardcoded fallback');
        $fields = [];
        
        // Define fields based on task group name
        switch ($taskGroup->name) {
            case 'Digital Media':
            case 'Web Dev':
            case 'Web Design':
            case 'Design QA':
            case 'Traffic':
            case 'Frontend':
            case 'Manila AI':
            case 'Migrations QA':
            case 'Web Development':
                // Common fields for all task groups
                $fields = [
                    [
                        'name' => 'task_type_complexity_id',
                        'type' => 'dropdown',
                        'required' => true,
                        'label' => 'Task Type & Complexity',
                        'options' => $this->getTaskTypeComplexityOptions($taskGroup)
                    ],
                    [
                        'name' => 'spon',
                        'type' => 'text',
                        'required' => false,
                        'label' => 'SPON'
                    ]
                ];
                break;
            default:
                // Default fields if task group doesn't match any known name
                $fields = [
                    [
                        'name' => 'task_type_complexity_id',
                        'type' => 'dropdown',
                        'required' => true,
                        'label' => 'Task Type & Complexity',
                        'options' => $this->getTaskTypeComplexityOptions($taskGroup)
                    ],
                    [
                        'name' => 'spon',
                        'type' => 'text',
                        'required' => false,
                        'label' => 'SPON'
                    ]
                ];
                break;
        }
        
        \Log::info('Returning hardcoded fields', ['count' => count($fields)]);
        return $fields;
    }

    /**
     * Generate validation rules for dynamic fields
     */
    private function getDynamicValidationRules(TaskGroup $taskGroup)
    {
        // Get validation rules from database
        $dbFields = $taskGroup->formFields()->where('is_active', true)->get();
        
        if ($dbFields->isNotEmpty()) {
            $rules = [];
            
            foreach ($dbFields as $field) {
                if ($field->validation_rules) {
                    $rules[$field->name] = implode('|', $field->getValidationRules());
                } elseif ($field->required) {
                    // If no specific validation rules but field is required
                    $rules[$field->name] = 'required';
                }
            }
            
            return $rules;
        }
        
        // Fallback to hardcoded rules if nothing in database yet
        $rules = [];
        
        // Define rules based on task group name
        switch ($taskGroup->name) {
            case 'Digital Media':
                $rules = [
                    'partner' => 'required',
                    'notes' => 'nullable|string'
                ];
                break;
                
            case 'Web Dev':
                $rules = [
                    'link' => 'nullable|url',
                    'notes' => 'nullable|string'
                ];
                break;
                
            case 'Web Design':
                $rules = [
                    'notes' => 'nullable|string'
                ];
                break;
                
            case 'Design QA':
                $rules = [
                    'partner' => 'required',
                    'platform' => 'required|in:web,mobile,both',
                    'notes' => 'nullable|string'
                ];
                break;
                
            case 'Traffic':
                $rules = [
                    'notes' => 'nullable|string'
                ];
                break;
                
            case 'Frontend':
                $rules = [
                    'notes' => 'nullable|string'
                ];
                break;
                
            case 'Manila AI':
                $rules = [
                    'partner' => 'required',
                    'notes' => 'nullable|string'
                ];
                break;
                
            case 'Migrations QA':
                $rules = [
                    'partner' => 'required',
                    'notes' => 'nullable|string'
                ];
                break;
        }
        
        return $rules;
    }

    /**
     * Extract dynamic field data from request
     */
    private function getDynamicFieldData(Request $request, TaskGroup $taskGroup)
    {
        // Get fields from database
        $dbFields = $taskGroup->formFields()->where('is_active', true)->get();
        
        if ($dbFields->isNotEmpty()) {
            $data = [];
            
            foreach ($dbFields as $field) {
                if ($request->has($field->name)) {
                    $data[$field->name] = $request->{$field->name};
                }
            }
            
            return $data;
        }
        
        // Fallback to hardcoded data extraction if nothing in database yet
        $data = [];
        
        // Extract data based on task group
        switch ($taskGroup->name) {
            case 'Digital Media':
                $data = [
                    'spon' => $request->spon,
                    'partner' => $request->partner,
                    'notes' => $request->notes
                ];
                break;
                
            case 'Web Dev':
                $data = [
                    'spon' => $request->spon,
                    'link' => $request->link,
                    'notes' => $request->notes
                ];
                break;
                
            case 'Web Design':
                $data = [
                    'spon' => $request->spon,
                    'notes' => $request->notes
                ];
                break;
                
            case 'Design QA':
                $data = [
                    'spon' => $request->spon,
                    'partner' => $request->partner,
                    'platform' => $request->platform,
                    'notes' => $request->notes
                ];
                break;
                
            case 'Traffic':
                $data = [
                    'spon' => $request->spon,
                    'notes' => $request->notes
                ];
                break;
                
            case 'Frontend':
                $data = [
                    'spon' => $request->spon,
                    'notes' => $request->notes
                ];
                break;
                
            case 'Manila AI':
                $data = [
                    'spon' => $request->spon,
                    'partner' => $request->partner,
                    'notes' => $request->notes
                ];
                break;
                
            case 'Migrations QA':
                $data = [
                    'spon' => $request->spon,
                    'partner' => $request->partner,
                    'notes' => $request->notes
                ];
                break;
        }
        
        return $data;
    }

    /**
     * Get task type complexity options for a task group
     */
    private function getTaskTypeComplexityOptions(TaskGroup $taskGroup)
    {
        $taskTypeComplexities = \App\Models\TaskTypeComplexity::with(['taskType', 'complexityLevel'])
            ->where('task_group_id', $taskGroup->id)
            ->where('is_active', true)
            ->get();
            
        $options = [];
        foreach ($taskTypeComplexities as $ttc) {
            $options[$ttc->id] = $ttc->taskType->name . ' - ' . $ttc->complexityLevel->name 
                . ' (' . $ttc->allocated_minutes . ' min)';
        }
        
        return $options;
    }
    
    /**
     * API endpoint to get form fields for a task group
     */
    public function getFormFields(Request $request, TaskGroup $taskGroup)
    {
        try {
            // Get the form fields
            $formFields = $this->getFormFieldsForTaskGroup($taskGroup);
            
            // Get task type complexities grouped by task type
            $taskTypeComplexities = [];
            $taskTypes = TaskType::where(function($query) use ($taskGroup) {
                $query->where('task_group_id', $taskGroup->id)
                    ->orWhereNull('task_group_id');
            })->where('is_active', true)->get();
            
            foreach ($taskTypes as $taskType) {
                $complexities = TaskTypeComplexity::with('complexityLevel')
                    ->where('task_type_id', $taskType->id)
                    ->where('task_group_id', $taskGroup->id)
                    ->where('is_active', true)
                    ->get()
                    ->map(function($ttc) {
                        return [
                            'id' => $ttc->id,
                            'display_name' => $ttc->complexityLevel->name . ' (' . $ttc->allocated_minutes . ' min)'
                        ];
                    });
                    
                if ($complexities->isNotEmpty()) {
                    $taskTypeComplexities[$taskType->name] = $complexities;
                }
            }
            
            // Return the data
            return response()->json([
                'fields' => $formFields,
                'taskTypeComplexities' => $taskTypeComplexities,
                'success' => true
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getFormFields', [
                'task_group_id' => $taskGroup->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to load form fields: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
    
    /**
     * API endpoint to get complexities for a task type within a task group
     */
    public function getTaskTypeComplexities(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'task_type_id' => 'required|exists:task_types,id',
                'task_group_id' => 'required|exists:task_groups,id'
            ]);
            
            // Log incoming request for debugging
            \Log::info('getTaskTypeComplexities request', [
                'task_type_id' => $request->task_type_id,
                'task_group_id' => $request->task_group_id,
                'user_id' => auth()->id() ?? 'unauthenticated',
                'request_ip' => $request->ip(),
                'request_url' => $request->fullUrl()
            ]);
            
            // Get raw data for debugging
            $rawComplexities = TaskTypeComplexity::where('task_type_id', $request->task_type_id)
                ->where('task_group_id', $request->task_group_id)
                ->where('is_active', true)
                ->get();
                
            \Log::info('Raw complexities found', [
                'count' => $rawComplexities->count(),
                'data' => $rawComplexities->toArray()
            ]);
            
            // If no complexities found, check if we should create default ones
            $createdDefaultComplexities = false;
            if ($rawComplexities->isEmpty()) {
                $taskType = TaskType::find($request->task_type_id);
                $complexityLevels = ComplexityLevel::where(function($query) use ($request) {
                    $query->where('task_group_id', $request->task_group_id)
                        ->orWhereNull('task_group_id');
                })->get();
                
                \Log::warning('No task type complexities found for valid task type', [
                    'task_type' => $taskType ? $taskType->toArray() : 'Not Found',
                    'available_complexities' => $complexityLevels->count(),
                    'complexity_levels' => $complexityLevels->pluck('name', 'id')->toArray()
                ]);
                
                // If we have complexity levels but no task type complexities, create default ones
                if ($taskType && $complexityLevels->isNotEmpty()) {
                    $defaultComplexities = $this->createDefaultTaskTypeComplexities(
                        $taskType, 
                        $complexityLevels, 
                        $request->task_group_id
                    );
                    
                    if ($defaultComplexities->isNotEmpty()) {
                        $rawComplexities = $defaultComplexities;
                        $createdDefaultComplexities = true;
                        
                        \Log::info('Created default complexities', [
                            'count' => $defaultComplexities->count(),
                            'complexities' => $defaultComplexities->pluck('id')->toArray()
                        ]);
                    }
                }
            }
            
            // Get complexities for this task type with proper relation loading
            $complexities = $rawComplexities->map(function($ttc) {
                // Make sure the relationships are loaded
                if (!$ttc->relationLoaded('complexityLevel')) {
                    $ttc->load('complexityLevel');
                }
                if (!$ttc->relationLoaded('taskType')) {
                    $ttc->load('taskType');
                }
                
                // Check for null relationships
                if (!$ttc->complexityLevel || !$ttc->taskType) {
                    \Log::error('Missing relationship for task type complexity', [
                        'id' => $ttc->id,
                        'has_complexity_level' => (bool)$ttc->complexityLevel,
                        'has_task_type' => (bool)$ttc->taskType
                    ]);
                    
                    // Skip invalid records
                    return null;
                }
                
                return [
                    'id' => $ttc->id,
                    'complexity_level_id' => $ttc->complexity_level_id,
                    'complexity_name' => $ttc->complexityLevel->name,
                    'allocated_minutes' => $ttc->allocated_minutes,
                    'task_type_name' => $ttc->taskType->name,
                    'is_default' => $ttc->is_default ?? false
                ];
            })->filter(); // Remove any null entries
                
            \Log::info('Returning formatted complexities', [
                'count' => $complexities->count(),
                'created_defaults' => $createdDefaultComplexities,
                'data' => $complexities->toArray()
            ]);
            
            return response()->json([
                'complexities' => $complexities,
                'created_defaults' => $createdDefaultComplexities,
                'success' => true
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getTaskTypeComplexities', [
                'task_type_id' => $request->task_type_id ?? null,
                'task_group_id' => $request->task_group_id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to load complexities: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
    
    /**
     * Create default task type complexity records when none exist
     */
    private function createDefaultTaskTypeComplexities($taskType, $complexityLevels, $taskGroupId)
    {
        \Log::info('Creating default task type complexities', [
            'task_type_id' => $taskType->id,
            'task_group_id' => $taskGroupId,
            'complexity_count' => $complexityLevels->count()
        ]);
        
        $createdComplexities = collect();
        $userId = auth()->id() ?? 1;  // Use authenticated user or default to admin
        
        // Default allocated minutes based on complexity name
        $defaultMinutes = [
            'low' => 30,
            'medium' => 60,
            'high' => 120,
            'minor' => 30,
            'major' => 60,
            'heavy' => 120,
        ];
        
        foreach ($complexityLevels as $complexity) {
            // Determine allocated minutes based on complexity name
            $name = strtolower($complexity->name);
            $minutes = 60; // Default 60 minutes
            
            // Check if the complexity name is in our defaults
            foreach ($defaultMinutes as $key => $value) {
                if (str_contains($name, $key)) {
                    $minutes = $value;
                    break;
                }
            }
            
            try {
                $ttc = TaskTypeComplexity::create([
                    'task_type_id' => $taskType->id,
                    'complexity_level_id' => $complexity->id,
                    'task_group_id' => $taskGroupId,
                    'allocated_minutes' => $minutes,
                    'is_active' => true,
                    'created_by' => $userId,
                    'updated_by' => $userId,
                    'is_default' => true,
                    'description' => 'Auto-generated default complexity'
                ]);
                
                $createdComplexities->push($ttc->load('complexityLevel', 'taskType'));
                
                \Log::info('Created default task type complexity', [
                    'id' => $ttc->id,
                    'task_type' => $taskType->name,
                    'complexity' => $complexity->name,
                    'minutes' => $minutes
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to create default task type complexity', [
                    'task_type_id' => $taskType->id,
                    'complexity_id' => $complexity->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return $createdComplexities;
    }
    
    /**
     * API endpoint to get all complexity levels for a task group
     * This is used to populate the complexity dropdown when creating a task
     */
    public function getTaskComplexityLevels(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'task_group_id' => 'required|exists:task_groups,id'
            ]);
            
            // Get all available complexity levels for this task group
            $complexityLevels = ComplexityLevel::where(function($query) use ($request) {
                $query->where('task_group_id', $request->task_group_id)
                    ->orWhereNull('task_group_id');
            })->get();
            
            \Log::info('Loaded complexity levels for task group', [
                'task_group_id' => $request->task_group_id,
                'count' => $complexityLevels->count(),
                'levels' => $complexityLevels->pluck('name', 'id')->toArray()
            ]);
            
            return response()->json([
                'complexity_levels' => $complexityLevels,
                'success' => true
            ])->header('Cache-Control', 'no-cache, no-store, must-revalidate')
              ->header('Pragma', 'no-cache')
              ->header('Expires', '0');
        } catch (\Exception $e) {
            \Log::error('Error in getTaskComplexityLevels', [
                'task_group_id' => $request->task_group_id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to load complexity levels: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    /**
     * Debug route to check and fix task type complexity records
     * This can be used to ensure that all task types have complexity records for a specific task group
     */
    public function debugTaskTypeComplexities(Request $request)
    {
        // Only available in local environment
        if (!app()->environment('local')) {
            abort(403, 'Only available in local environment');
        }
        
        $taskTypeId = $request->input('task_type_id');
        $taskGroupId = $request->input('task_group_id');
        $shouldFix = $request->input('fix', false);
        
        $response = [
            'debug_time' => now()->toDateTimeString(),
            'debug_message' => 'Task Type Complexity Debugging Tool'
        ];
        
        // If specific task type and group are provided, check those
        if ($taskTypeId && $taskGroupId) {
            $taskType = TaskType::find($taskTypeId);
            $taskGroup = TaskGroup::find($taskGroupId);
            
            if (!$taskType || !$taskGroup) {
                $response['error'] = 'Task type or task group not found';
                $response['task_type_exists'] = (bool)$taskType;
                $response['task_group_exists'] = (bool)$taskGroup;
                return response()->json($response);
            }
            
            $response['task_type'] = $taskType->toArray();
            $response['task_group'] = $taskGroup->toArray();
            
            // Check for existing complexities
            $existingComplexities = TaskTypeComplexity::where('task_type_id', $taskTypeId)
                ->where('task_group_id', $taskGroupId)
                ->where('is_active', true)
                ->with(['complexityLevel', 'taskType'])
                ->get();
                
            $response['existing_complexities'] = $existingComplexities->toArray();
            $response['existing_count'] = $existingComplexities->count();
            
            // Get available complexity levels
            $availableComplexities = ComplexityLevel::where(function($query) use ($taskGroupId) {
                $query->where('task_group_id', $taskGroupId)
                    ->orWhereNull('task_group_id');
            })->get();
            
            $response['available_complexities'] = $availableComplexities->toArray();
            $response['available_count'] = $availableComplexities->count();
            
            // Fix missing complexities if requested
            if ($shouldFix && $existingComplexities->isEmpty() && $availableComplexities->isNotEmpty()) {
                $createdComplexities = $this->createDefaultTaskTypeComplexities(
                    $taskType,
                    $availableComplexities,
                    $taskGroupId
                );
                
                $response['fixed'] = true;
                $response['created_complexities'] = $createdComplexities->toArray();
                $response['created_count'] = $createdComplexities->count();
            }
        } else {
            // Show all task types and task groups
            $response['task_types'] = TaskType::where('is_active', true)->get()->toArray();
            $response['task_groups'] = TaskGroup::where('is_active', true)->get()->toArray();
            $response['complexity_levels'] = ComplexityLevel::all()->toArray();
            
            // Count task type complexities
            $totalComplexities = TaskTypeComplexity::where('is_active', true)->count();
            $response['total_complexities'] = $totalComplexities;
            
            // List some recent task type complexities
            $response['recent_complexities'] = TaskTypeComplexity::with(['taskType', 'complexityLevel', 'taskGroup'])
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->toArray();
        }
        
        return response()->json($response);
    }
} 