<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftSchedule extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'days_of_week',
        'description',
        'is_active'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'days_of_week' => 'array',
        'is_active' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];
    
    /**
     * Get the users assigned to this shift schedule
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    /**
     * Format the days of week as a readable string
     */
    public function getFormattedDaysAttribute()
    {
        if (empty($this->days_of_week)) {
            return 'No days specified';
        }
        
        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $formattedDays = [];
        
        foreach ($this->days_of_week as $day) {
            $formattedDays[] = $dayNames[$day] ?? '';
        }
        
        return implode(', ', $formattedDays);
    }
}
