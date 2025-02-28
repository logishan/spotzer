<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ComplexityLevel;

class ComplexityLevelsSeeder extends Seeder
{
    public function run()
    {
        $levels = [
            ['name' => 'Minor', 'description' => 'Simple tasks requiring minimal effort'],
            ['name' => 'Major', 'description' => 'Moderate complexity tasks'],
            ['name' => 'Heavy', 'description' => 'Complex tasks requiring significant effort']
        ];

        foreach ($levels as $level) {
            ComplexityLevel::create($level);
        }
    }
} 