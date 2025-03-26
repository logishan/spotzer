<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'date',
        'time_in',
        'time_out',
        'status',
        'notes'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime:H:i',
        'time_out' => 'datetime:H:i',
    ];
    
    /**
     * Get the user this attendance record belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Calculate hours worked based on time in and time out
     */
    public function getHoursWorkedAttribute()
    {
        if (!$this->time_in || !$this->time_out) {
            return 0;
        }
        
        $timeIn = new \DateTime($this->time_in);
        $timeOut = new \DateTime($this->time_out);
        
        $interval = $timeIn->diff($timeOut);
        
        return $interval->h + ($interval->i / 60);
    }
}
