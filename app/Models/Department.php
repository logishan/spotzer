<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'parent_department_id'
    ];

    /**
     * Get the parent department
     */
    public function parentDepartment()
    {
        return $this->belongsTo(Department::class, 'parent_department_id');
    }

    /**
     * Get the child departments
     */
    public function childDepartments()
    {
        return $this->hasMany(Department::class, 'parent_department_id');
    }

    /**
     * Get the users belonging to this department
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_departments')
                    ->withPivot('is_department_head')
                    ->withTimestamps();
    }

    /**
     * Get the department head
     */
    public function departmentHead()
    {
        return $this->users()
                    ->wherePivot('is_department_head', true)
                    ->first();
    }
} 