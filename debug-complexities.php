<?php

// Load Laravel environment
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get command line args if provided
$taskTypeId = $argv[1] ?? null;
$taskGroupId = $argv[2] ?? null;

echo "=== COMPLEXITY LEVELS DEBUG TOOL ===\n\n";

if ($taskTypeId && $taskGroupId) {
    echo "🔍 LOOKING UP SPECIFIC TASK TYPE & TASK GROUP\n";
    echo "Task Type ID: $taskTypeId\n";
    echo "Task Group ID: $taskGroupId\n\n";
} else {
    echo "⚠️ No specific task type and group provided. Showing summary data.\n\n";
}

// Check all task groups
echo "=== TASK GROUPS ===\n";
$taskGroups = \App\Models\TaskGroup::all();
echo "Total task groups: " . $taskGroups->count() . "\n";
foreach ($taskGroups as $group) {
    echo "- ID: {$group->id}, Name: {$group->name}" . ($group->is_active ? "" : " (inactive)") . "\n";
}
echo "\n";

// Check all task types 
echo "=== TASK TYPES ===\n";
$taskTypes = \App\Models\TaskType::all();
echo "Total task types: " . $taskTypes->count() . "\n";
foreach ($taskTypes as $type) {
    echo "- ID: {$type->id}, Name: {$type->name}, Group ID: " . ($type->task_group_id ?? "NULL") . 
         ($type->is_active ? "" : " (inactive)") . "\n";
}
echo "\n";

// Check all complexity levels
echo "=== COMPLEXITY LEVELS ===\n";
$complexityLevels = \App\Models\ComplexityLevel::all();
echo "Total complexity levels: " . $complexityLevels->count() . "\n";
foreach ($complexityLevels as $level) {
    echo "- ID: {$level->id}, Name: {$level->name}, Group ID: " . ($level->task_group_id ?? "NULL") . "\n";
}
echo "\n";

// Check task type complexities
echo "=== TASK TYPE COMPLEXITIES ===\n";
$ttcs = \App\Models\TaskTypeComplexity::all();
echo "Total task type complexities: " . $ttcs->count() . "\n";
foreach ($ttcs as $ttc) {
    echo "- ID: {$ttc->id}, Type ID: {$ttc->task_type_id}, Level ID: {$ttc->complexity_level_id}, " . 
         "Group ID: " . ($ttc->task_group_id ?? "NULL") . 
         ($ttc->is_active ? "" : " (inactive)") .
         ", Minutes: {$ttc->allocated_minutes}\n";
}
echo "\n";

// If specific IDs were provided, perform the exact same query as the debug route
if ($taskTypeId && $taskGroupId) {
    echo "=== RUNNING DEBUG QUERY ===\n";
    
    // Same query as debug route
    $complexities = \App\Models\TaskTypeComplexity::where('task_type_id', $taskTypeId)
        ->where('task_group_id', $taskGroupId)
        ->where('is_active', true)
        ->with(['complexityLevel', 'taskType'])
        ->get();
        
    echo "Complexities found: " . $complexities->count() . "\n";
    foreach ($complexities as $ttc) {
        echo "- TTC ID: {$ttc->id}, Type: {$ttc->taskType->name}, Level: {$ttc->complexityLevel->name}, " . 
             "Minutes: {$ttc->allocated_minutes}\n";
    }
    echo "\n";
    
    // Second query from debug route
    $availableComplexities = \App\Models\ComplexityLevel::where(function($query) use ($taskGroupId) {
        $query->where('task_group_id', $taskGroupId)
            ->orWhereNull('task_group_id');
    })->get();
    
    echo "Available complexity levels: " . $availableComplexities->count() . "\n";
    foreach ($availableComplexities as $level) {
        echo "- ID: {$level->id}, Name: {$level->name}, Group ID: " . ($level->task_group_id ?? "NULL") . "\n";
    }
    
    // Data correction option for null task_group_id in task_type_complexities
    echo "\n=== POTENTIAL FIXES ===\n";
    
    $missingGroupTtcs = \App\Models\TaskTypeComplexity::whereNull('task_group_id')
        ->where('task_type_id', $taskTypeId)
        ->where('is_active', true)
        ->get();
        
    echo "Task type complexities for this task type with NULL task_group_id: " . $missingGroupTtcs->count() . "\n";
    
    if ($missingGroupTtcs->count() > 0) {
        echo "These records might need task_group_id to be set to $taskGroupId\n";
    }
} 