<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTypeComplexityHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'task_type_complexity_id',
        'previous_allocated_minutes',
        'new_allocated_minutes',
        'changed_by',
        'changed_at',
        'reason'
    ];

    protected $casts = [
        'changed_at' => 'datetime'
    ];

    public function taskTypeComplexity()
    {
        return $this->belongsTo(TaskTypeComplexity::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
} 