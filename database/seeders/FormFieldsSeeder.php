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
            
            // Group-specific fields
            switch ($taskGroup->name) {
                case 'Digital Media':
                    $this->seedDigitalMediaFields($taskGroup);
                    break;
                    
                case 'Web Dev':
                    $this->seedWebDevFields($taskGroup);
                    break;
                    
                case 'Web Design':
                    $this->seedWebDesignFields($taskGroup);
                    break;
                    
                case 'Design QA':
                    $this->seedDesignQAFields($taskGroup);
                    break;
                    
                case 'Traffic':
                    $this->seedTrafficFields($taskGroup);
                    break;
                    
                case 'Frontend':
                    $this->seedFrontendFields($taskGroup);
                    break;
                    
                case 'Manila AI':
                    $this->seedManilaAIFields($taskGroup);
                    break;
                    
                case 'Migrations QA':
                    $this->seedMigrationsQAFields($taskGroup);
                    break;
            }
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
        
        // Keep the original combined field for backward compatibility but make it hidden
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'task_type_complexity_id',
            'label' => 'Task Type & Complexity',
            'type' => 'hidden',
            'required' => true,
            'display_order' => 30,
            'options' => [], // Will be populated dynamically from task_type_complexities
            'validation_rules' => ['required', 'exists:task_type_complexities,id'],
            'is_active' => true,
        ]);
    }
    
    /**
     * Seed Digital Media specific fields
     */
    private function seedDigitalMediaFields(TaskGroup $taskGroup)
    {
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
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'partner',
            'label' => 'Partner',
            'type' => 'dropdown',
            'required' => true,
            'display_order' => 40,
            'options' => [], // To be populated with partners
            'validation_rules' => ['required'],
            'is_active' => true,
        ]);
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'region',
            'label' => 'Region',
            'type' => 'radio',
            'required' => true,
            'display_order' => 50,
            'options' => [
                'na' => 'North America',
                'emea' => 'EMEA',
                'apac' => 'APAC',
                'latam' => 'LATAM'
            ],
            'validation_rules' => ['required', 'in:na,emea,apac,latam'],
            'is_active' => true,
        ]);
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'notes',
            'label' => 'Notes',
            'type' => 'textarea',
            'required' => false,
            'display_order' => 60,
            'options' => null,
            'validation_rules' => ['nullable', 'string'],
            'is_active' => true,
        ]);
    }
    
    /**
     * Seed Web Dev specific fields
     */
    private function seedWebDevFields(TaskGroup $taskGroup)
    {
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
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'link',
            'label' => 'Link',
            'type' => 'text',
            'required' => false,
            'display_order' => 40,
            'options' => null,
            'validation_rules' => ['nullable', 'url'],
            'is_active' => true,
        ]);
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'region',
            'label' => 'Region',
            'type' => 'radio',
            'required' => true,
            'display_order' => 50,
            'options' => [
                'na' => 'North America',
                'emea' => 'EMEA',
                'apac' => 'APAC',
                'latam' => 'LATAM'
            ],
            'validation_rules' => ['required', 'in:na,emea,apac,latam'],
            'is_active' => true,
        ]);
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'notes',
            'label' => 'Notes',
            'type' => 'textarea',
            'required' => false,
            'display_order' => 60,
            'options' => null,
            'validation_rules' => ['nullable', 'string'],
            'is_active' => true,
        ]);
    }
    
    /**
     * Seed Web Design specific fields
     */
    private function seedWebDesignFields(TaskGroup $taskGroup)
    {
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
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'region',
            'label' => 'Region',
            'type' => 'radio',
            'required' => true,
            'display_order' => 40,
            'options' => [
                'na' => 'North America',
                'emea' => 'EMEA',
                'apac' => 'APAC',
                'latam' => 'LATAM'
            ],
            'validation_rules' => ['required', 'in:na,emea,apac,latam'],
            'is_active' => true,
        ]);
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'notes',
            'label' => 'Notes',
            'type' => 'textarea',
            'required' => false,
            'display_order' => 50,
            'options' => null,
            'validation_rules' => ['nullable', 'string'],
            'is_active' => true,
        ]);
    }
    
    /**
     * Seed Design QA specific fields
     */
    private function seedDesignQAFields(TaskGroup $taskGroup)
    {
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
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'partner',
            'label' => 'Partner',
            'type' => 'dropdown',
            'required' => true,
            'display_order' => 40,
            'options' => [], // To be populated with partners
            'validation_rules' => ['required'],
            'is_active' => true,
        ]);
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'platform',
            'label' => 'Platform',
            'type' => 'dropdown',
            'required' => true,
            'display_order' => 50,
            'options' => [
                'web' => 'Web',
                'mobile' => 'Mobile',
                'both' => 'Both'
            ],
            'validation_rules' => ['required', 'in:web,mobile,both'],
            'is_active' => true,
        ]);
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'region',
            'label' => 'Region',
            'type' => 'radio',
            'required' => true,
            'display_order' => 60,
            'options' => [
                'na' => 'North America',
                'emea' => 'EMEA',
                'apac' => 'APAC',
                'latam' => 'LATAM'
            ],
            'validation_rules' => ['required', 'in:na,emea,apac,latam'],
            'is_active' => true,
        ]);
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'notes',
            'label' => 'Notes',
            'type' => 'textarea',
            'required' => false,
            'display_order' => 70,
            'options' => null,
            'validation_rules' => ['nullable', 'string'],
            'is_active' => true,
        ]);
    }
    
    /**
     * Seed Traffic specific fields
     */
    private function seedTrafficFields(TaskGroup $taskGroup)
    {
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
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'notes',
            'label' => 'Notes',
            'type' => 'textarea',
            'required' => false,
            'display_order' => 40,
            'options' => null,
            'validation_rules' => ['nullable', 'string'],
            'is_active' => true,
        ]);
    }
    
    /**
     * Seed Frontend specific fields
     */
    private function seedFrontendFields(TaskGroup $taskGroup)
    {
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
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'region',
            'label' => 'Region',
            'type' => 'radio',
            'required' => true,
            'display_order' => 40,
            'options' => [
                'na' => 'North America',
                'emea' => 'EMEA',
                'apac' => 'APAC',
                'latam' => 'LATAM'
            ],
            'validation_rules' => ['required', 'in:na,emea,apac,latam'],
            'is_active' => true,
        ]);
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'notes',
            'label' => 'Notes',
            'type' => 'textarea',
            'required' => false,
            'display_order' => 50,
            'options' => null,
            'validation_rules' => ['nullable', 'string'],
            'is_active' => true,
        ]);
    }
    
    /**
     * Seed Manila AI specific fields
     */
    private function seedManilaAIFields(TaskGroup $taskGroup)
    {
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
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'partner',
            'label' => 'Partner',
            'type' => 'dropdown',
            'required' => true,
            'display_order' => 40,
            'options' => [], // To be populated with partners
            'validation_rules' => ['required'],
            'is_active' => true,
        ]);
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'region',
            'label' => 'Region',
            'type' => 'radio',
            'required' => true,
            'display_order' => 50,
            'options' => [
                'na' => 'North America',
                'emea' => 'EMEA',
                'apac' => 'APAC',
                'latam' => 'LATAM'
            ],
            'validation_rules' => ['required', 'in:na,emea,apac,latam'],
            'is_active' => true,
        ]);
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'notes',
            'label' => 'Notes',
            'type' => 'textarea',
            'required' => false,
            'display_order' => 60,
            'options' => null,
            'validation_rules' => ['nullable', 'string'],
            'is_active' => true,
        ]);
    }
    
    /**
     * Seed Migrations QA specific fields
     */
    private function seedMigrationsQAFields(TaskGroup $taskGroup)
    {
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
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'partner',
            'label' => 'Partner',
            'type' => 'dropdown',
            'required' => true,
            'display_order' => 40,
            'options' => [], // To be populated with partners
            'validation_rules' => ['required'],
            'is_active' => true,
        ]);
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'region',
            'label' => 'Region',
            'type' => 'radio',
            'required' => true,
            'display_order' => 50,
            'options' => [
                'na' => 'North America',
                'emea' => 'EMEA',
                'apac' => 'APAC',
                'latam' => 'LATAM'
            ],
            'validation_rules' => ['required', 'in:na,emea,apac,latam'],
            'is_active' => true,
        ]);
        
        FormField::create([
            'task_group_id' => $taskGroup->id,
            'name' => 'notes',
            'label' => 'Notes',
            'type' => 'textarea',
            'required' => false,
            'display_order' => 60,
            'options' => null,
            'validation_rules' => ['nullable', 'string'],
            'is_active' => true,
        ]);
    }
}
