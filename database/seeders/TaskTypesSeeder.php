<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskType;
use App\Models\TaskTypeComplexity;
use App\Models\ComplexityLevel;

class TaskTypesSeeder extends Seeder
{
    public function run()
    {
        $taskTypes = [
            [
                'name' => 'Research',
                'description' => 'Research and analysis tasks',
                'complexities' => [
                    'Minor' => 60,  // 1 hour
                    'Major' => 120, // 2 hours
                    'Heavy' => 300  // 5 hours
                ]
            ],
            [
                'name' => 'Development',
                'description' => 'Software development tasks',
                'complexities' => [
                    'Minor' => 120,  // 2 hours
                    'Major' => 240,  // 4 hours
                    'Heavy' => 480   // 8 hours
                ]
            ],
            [
                'name' => 'Testing',
                'description' => 'QA and testing tasks',
                'complexities' => [
                    'Minor' => 30,   // 30 minutes
                    'Major' => 90,   // 1.5 hours
                    'Heavy' => 180   // 3 hours
                ]
            ]
        ];

        foreach ($taskTypes as $typeData) {
            $taskType = TaskType::create([
                'name' => $typeData['name'],
                'description' => $typeData['description'],
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1
            ]);

            foreach ($typeData['complexities'] as $complexityName => $minutes) {
                $complexityLevel = ComplexityLevel::where('name', $complexityName)->first();
                
                TaskTypeComplexity::create([
                    'task_type_id' => $taskType->id,
                    'complexity_level_id' => $complexityLevel->id,
                    'allocated_minutes' => $minutes,
                    'effective_from' => now(),
                    'created_by' => 1,
                    'updated_by' => 1
                ]);
            }
        }
    }
} 