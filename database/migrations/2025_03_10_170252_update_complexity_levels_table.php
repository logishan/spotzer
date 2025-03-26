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
        Schema::table('complexity_levels', function (Blueprint $table) {
            $table->foreignId('task_group_id')->nullable()->after('id')->constrained('task_groups')->onDelete('set null');
            $table->dropColumn('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complexity_levels', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->dropForeign(['task_group_id']);
            $table->dropColumn('task_group_id');
        });
    }
};
