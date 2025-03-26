<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplexityLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'task_group_id'
    ];

    public function taskTypeComplexities()
    {
        return $this->hasMany(TaskTypeComplexity::class);
    }

    public function taskTypes()
    {
        return $this->belongsToMany(TaskType::class, 'task_type_complexities')
            ->withPivot(['allocated_minutes'])
            ->withTimestamps();
    }
    
    public function taskGroup()
    {
        return $this->belongsTo(TaskGroup::class);
    }
} 