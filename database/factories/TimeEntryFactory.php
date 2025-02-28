<?php

namespace Database\Factories;

use App\Models\TimeEntry;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimeEntryFactory extends Factory
{
    protected $model = TimeEntry::class;

    public function definition()
    {
        $startTime = $this->faker->dateTimeBetween('-1 month', 'now');
        $endTime = clone $startTime;
        $endTime->modify('+' . rand(30, 240) . ' minutes');

        return [
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_minutes' => $endTime->diff($startTime)->i,
            'is_manual_entry' => false,
            'notes' => $this->faker->optional()->sentence
        ];
    }

    public function manual()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_manual_entry' => true
            ];
        });
    }
} 