<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'task_type_complexity_id',
        'status',
        'priority',
        'created_by'
    ];

    protected $casts = [
        'status' => 'string',
        'priority' => 'string',
    ];
    
    /**
     * The attributes that should have default values.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'todo',
        'priority' => 'medium',
    ];

    /**
     * Get the task groups that this task belongs to.
     */
    public function taskGroups(): BelongsToMany
    {
        return $this->belongsToMany(TaskGroup::class, 'task_group_tasks');
    }

    /**
     * Get the task type complexity associated with the task.
     */
    public function taskTypeComplexity(): BelongsTo
    {
        return $this->belongsTo(TaskTypeComplexity::class);
    }

    /**
     * Get the user who created the task.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the task assignments.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(TaskAssignment::class);
    }

    /**
     * Get the task comments.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }
} 