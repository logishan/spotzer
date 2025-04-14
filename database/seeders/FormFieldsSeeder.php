<?php

namespace Database\Seeders;

use App\Models\FormField;
use App\Models\TaskGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing form fields
        FormField::truncate();
        
        // Get all task groups
        $taskGroups = TaskGroup::all();
        
        foreach ($taskGroups as $taskGroup) {
            // Common fields for all task groups
            $this->seedCommonFields($taskGroup);
        }
    }
    
    /**
     * Seed common fields for all task groups
     */
    private function seedCommonFields(TaskGroup $taskGroup)
    {
        // Create task type field
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'task_type',
            'label' => 'Task Type',
            'type' => 'dropdown',
            'required' => true,
            'display_order' => 10,
            'options' => [], // Will be populated dynamically from task_types
            'validation_rules' => ['required', 'exists:task_types,id'],
            'is_active' => true,
        ]);
        
        // Create complexity level field
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'complexity',
            'label' => 'Task Complexity',
            'type' => 'dropdown',
            'required' => true,
            'display_order' => 20,
            'options' => [], // Will be populated dynamically based on selected task type
            'validation_rules' => ['required', 'exists:complexity_levels,id'],
            'is_active' => true,
        ]);
        
        // Create SPON field
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'spon',
            'label' => 'SPON',
            'type' => 'text',
            'required' => false,
            'display_order' => 30,
            'options' => null,
            'validation_rules' => ['nullable', 'string'],
            'is_active' => true,
        ]);
        
        // Keep the original combined field for backward compatibility but make it hidden
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'task_type_complexity_id',
            'label' => 'Task Type & Complexity',
            'type' => 'hidden',
            'required' => true,
            'display_order' => 40,
            'options' => [], // Will be populated dynamically from task_type_complexities
            'validation_rules' => ['required', 'exists:task_type_complexities,id'],
            'is_active' => true,
        ]);
    }
} 