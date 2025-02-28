<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskTypeComplexity;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'task_type_complexity_id' => TaskTypeComplexity::factory(),
            'status' => $this->faker->randomElement([
                Task::STATUS_TODO,
                Task::STATUS_IN_PROGRESS,
                Task::STATUS_PAUSED,
                Task::STATUS_COMPLETED
            ]),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'urgent']),
            'created_by' => User::factory()
        ];
    }

    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Task::STATUS_COMPLETED
            ];
        });
    }

    public function inProgress()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Task::STATUS_IN_PROGRESS
            ];
        });
    }
} 