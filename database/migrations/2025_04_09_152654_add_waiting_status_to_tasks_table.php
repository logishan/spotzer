<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For PostgreSQL, we need to determine the actual enum name first
        // In PostgreSQL, when using $table->enum(), Laravel creates a custom type
        // typically named as tablename_columnname_enum
        
        // Check if we're using PostgreSQL
        if (DB::connection()->getDriverName() === 'pgsql') {
            // First check if enum type exists
            $enumExists = DB::select("
                SELECT 1 
                FROM pg_type 
                WHERE typname = 'tasks_status_enum'
            ");
            
            if (empty($enumExists)) {
                // If the enum doesn't exist with that name, it might have a different name
                // Or we might need to recreate the column
                
                // Create a backup of the existing data
                Schema::table('tasks', function (Blueprint $table) {
                    $table->string('status_backup')->nullable();
                });
                
                DB::statement('UPDATE tasks SET status_backup = status');
                
                // Update the column to use the new enum values
                Schema::table('tasks', function (Blueprint $table) {
                    $table->dropColumn('status');
                });
                
                Schema::table('tasks', function (Blueprint $table) {
                    $table->enum('status', ['todo', 'in_progress', 'waiting', 'paused', 'completed'])->default('todo');
                });
                
                // Restore data (with validation to ensure valid enum values)
                DB::statement("
                    UPDATE tasks 
                    SET status = CASE 
                        WHEN status_backup IN ('todo', 'in_progress', 'paused', 'completed') THEN status_backup
                        ELSE 'todo' 
                    END
                ");
                
                // Remove backup column
                Schema::table('tasks', function (Blueprint $table) {
                    $table->dropColumn('status_backup');
                });
            } else {
                // The enum exists with the expected name, so add the new value
                DB::statement("ALTER TYPE tasks_status_enum ADD VALUE IF NOT EXISTS 'waiting'");
            }
        } else {
            // For MySQL or other databases that handle enums differently
            // We'd need a different approach, typically recreating the column
            // with the new enum values
            
            // This is a simplified example for MySQL
            if (DB::connection()->getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('todo', 'in_progress', 'waiting', 'paused', 'completed') NOT NULL DEFAULT 'todo'");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot remove enum values in PostgreSQL, so this is a no-op
        // In production, you would need to create a new enum type without 'waiting'
        // and alter the column to use the new type
        
        // For MySQL, we could revert the column definition
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('todo', 'in_progress', 'paused', 'completed') NOT NULL DEFAULT 'todo'");
        }
    }
};
