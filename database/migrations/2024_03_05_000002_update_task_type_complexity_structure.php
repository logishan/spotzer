<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('task_type_complexities', function (Blueprint $table) {
            // Drop the effective date columns
            $table->dropColumn(['effective_from', 'effective_to']);
            
            // Ensure we have the is_active column (if it doesn't exist)
            if (!Schema::hasColumn('task_type_complexities', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    public function down()
    {
        Schema::table('task_type_complexities', function (Blueprint $table) {
            // Restore the effective date columns
            $table->timestamp('effective_from')->nullable();
            $table->timestamp('effective_to')->nullable();
        });
    }
}; 