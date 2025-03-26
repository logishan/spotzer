<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
        'created_by',
        'updated_by',
        'task_group_id'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function complexities()
    {
        return $this->hasMany(TaskTypeComplexity::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    public function taskGroup()
    {
        return $this->belongsTo(TaskGroup::class);
    }
} 