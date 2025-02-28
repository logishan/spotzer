<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        $permissions = [
            ['name' => 'Manage Users', 'slug' => 'manage_users'],
            ['name' => 'Manage Departments', 'slug' => 'manage_departments'],
            ['name' => 'Manage Tasks', 'slug' => 'manage_tasks'],
            ['name' => 'Manage Task Types', 'slug' => 'manage_task_types'],
            ['name' => 'Approve Time Entries', 'slug' => 'approve_time_entries'],
            ['name' => 'View Reports', 'slug' => 'view_reports'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Create roles with permissions
        $roles = [
            [
                'name' => 'Super Admin',
                'level' => 10,
                'permissions' => ['manage_users', 'manage_departments', 'manage_tasks', 
                                'manage_task_types', 'approve_time_entries', 'view_reports']
            ],
            [
                'name' => 'Department Head',
                'level' => 8,
                'permissions' => ['manage_tasks', 'approve_time_entries', 'view_reports']
            ],
            [
                'name' => 'Team Lead',
                'level' => 5,
                'permissions' => ['manage_tasks', 'approve_time_entries']
            ],
            [
                'name' => 'Employee',
                'level' => 1,
                'permissions' => []
            ]
        ];

        foreach ($roles as $roleData) {
            $role = Role::create([
                'name' => $roleData['name'],
                'level' => $roleData['level']
            ]);

            $permissions = Permission::whereIn('slug', $roleData['permissions'])->get();
            $role->permissions()->attach($permissions);
        }
    }
} 