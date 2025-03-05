<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        // Update Employee role level to 0
        $employeeRole = Role::where('name', 'Employee')->first();
        if ($employeeRole) {
            $employeeRole->update(['level' => 0]);
        } else {
            // Create Employee role if it doesn't exist
            Role::create([
                'name' => 'Employee',
                'slug' => 'employee',
                'description' => 'Regular employee with basic access',
                'level' => 0,
            ]);
        }
    }

    public function down()
    {
        // Revert Employee role level back to 3
        $employeeRole = Role::where('name', 'Employee')->first();
        if ($employeeRole) {
            $employeeRole->update(['level' => 3]);
        }
    }
}; 