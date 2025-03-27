<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class TeamLeaderRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Team Leader',
            'description' => 'Team Leader',
            'level' => 5,
        ]);
        
        $this->command->info('Team Leader role created successfully!');
    }
} 