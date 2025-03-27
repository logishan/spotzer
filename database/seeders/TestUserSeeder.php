<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find a valid role ID from the database
        $role = Role::first();
        
        if (!$role) {
            $this->command->error('No roles found. Please run RoleSeeder first.');
            return;
        }
        
        try {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@email.com',
                'password' => Hash::make('password'),
                'status' => 'active',
                'department_id' => 1, // Make sure this department exists
                'role_id' => $role->id, // Use an existing role ID
            ]);
            
            // Optional: Sync the role using the relationship as well
            $user->roles()->sync([$role->id]);
            
            $this->command->info('Test user created successfully!');
        } catch (\Exception $e) {
            $this->command->error('Error creating user: ' . $e->getMessage());
        }
    }
} 