<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_group_access')
            ->withPivot(['can_view', 'can_edit', 'granted_by'])
            ->withTimestamps();
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_group_tasks')
            ->withTimestamps();
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'task_group_departments')
            ->withTimestamps();
    }

    public function taskTypes()
    {
        return $this->hasMany(TaskType::class);
    }

    public function taskTypeComplexities()
    {
        return $this->hasMany(TaskTypeComplexity::class);
    }

    public function complexityLevels()
    {
        return $this->hasMany(ComplexityLevel::class);
    }
} 