<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskTypeComplexity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'task_type_id',
        'complexity_level_id',
        'allocated_minutes',
        'is_active',
        'created_by',
        'updated_by',
        'task_group_id',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Scope a query to only include active task type complexities.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function taskType()
    {
        return $this->belongsTo(TaskType::class);
    }

    public function complexityLevel()
    {
        return $this->belongsTo(ComplexityLevel::class);
    }

    public function taskGroup()
    {
        return $this->belongsTo(TaskGroup::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function recordHistory($previousMinutes, $newMinutes, $reason = null)
    {
        return TaskTypeComplexityHistory::create([
            'task_type_complexity_id' => $this->id,
            'previous_allocated_minutes' => $previousMinutes,
            'new_allocated_minutes' => $newMinutes,
            'changed_by' => auth()->id(),
            'changed_at' => now(),
            'reason' => $reason
        ]);
    }
} 