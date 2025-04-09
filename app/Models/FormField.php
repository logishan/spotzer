<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormField extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_group_id',
        'name',
        'label',
        'type',
        'required',
        'display_order',
        'options',
        'validation_rules',
        'is_active'
    ];

    protected $casts = [
        'required' => 'boolean',
        'is_active' => 'boolean',
        'options' => 'array',
        'validation_rules' => 'array',
    ];

    /**
     * Get the task group that this field belongs to
     */
    public function taskGroup(): BelongsTo
    {
        return $this->belongsTo(TaskGroup::class);
    }

    /**
     * Get the formatted options for dropdown/radio fields
     *
     * @return array
     */
    public function getFormattedOptions(): array
    {
        // Handle special case for task_type_complexity_id field
        if ($this->name === 'task_type_complexity_id') {
            // Get all task type complexities for this task group
            $taskTypeComplexities = \App\Models\TaskTypeComplexity::with(['taskType', 'complexityLevel'])
                ->where('task_group_id', $this->task_group_id)
                ->where('is_active', true)
                ->get();
                
            $options = [];
            foreach ($taskTypeComplexities as $ttc) {
                $options[$ttc->id] = $ttc->taskType->name . ' - ' . $ttc->complexityLevel->name 
                    . ' (' . $ttc->allocated_minutes . ' min)';
            }
            
            return $options;
        }
        
        // Handle special case for task_type field
        if ($this->name === 'task_type') {
            // Get all task types for this task group or any global ones
            return \App\Models\TaskType::where(function($query) {
                $query->where('task_group_id', $this->task_group_id)
                      ->orWhereNull('task_group_id');
            })
            ->where('is_active', true)
            ->pluck('name', 'id')
            ->toArray();
        }
        
        // Handle special case for complexity field
        if ($this->name === 'complexity') {
            // Get all complexity levels for this task group or any global ones
            return \App\Models\ComplexityLevel::where(function($query) {
                $query->where('task_group_id', $this->task_group_id)
                      ->orWhereNull('task_group_id');
            })
            ->pluck('name', 'id')
            ->toArray();
        }
        
        // For all other fields, return the options from the database
        return $this->options ?? [];
    }

    /**
     * Get validation rules for this field
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return $this->validation_rules ?? [];
    }
}
