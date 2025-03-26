<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('task_type_complexities', function (Blueprint $table) {
            $table->foreignId('task_group_id')->nullable()->after('id')->constrained('task_groups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_type_complexities', function (Blueprint $table) {
            $table->dropForeign(['task_group_id']);
            $table->dropColumn('task_group_id');
        });
    }
};
