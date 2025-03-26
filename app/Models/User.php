<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'google_id',
        'status',
        'role_id',
        'is_active',
        'department_id',
        'shift_schedule_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's primary department
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the roles associated with the user
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')
                    ->withTimestamps();
    }

    /**
     * Get all departments associated with the user
     */
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'user_departments')
                    ->withTimestamps();
    }

    /**
     * Get all task groups associated with the user
     */
    public function taskGroups()
    {
        return $this->belongsToMany(TaskGroup::class, 'user_task_groups')
                    ->withTimestamps();
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Get the user's primary role
     */
    public function primaryRole()
    {
        return $this->roles()->orderBy('level')->first();
    }

    /**
     * Get the user's shift schedule
     */
    public function shiftSchedule()
    {
        return $this->belongsTo(ShiftSchedule::class);
    }

    /**
     * Get the user's attendance records
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Check if user is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
}
