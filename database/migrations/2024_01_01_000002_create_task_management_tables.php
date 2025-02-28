<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('task_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('complexity_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('task_type_complexities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_type_id')->constrained();
            $table->foreignId('complexity_level_id')->constrained();
            $table->integer('allocated_minutes');
            $table->timestamp('effective_from');
            $table->timestamp('effective_to')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('task_type_complexity_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_type_complexity_id')->constrained();
            $table->integer('previous_allocated_minutes');
            $table->integer('new_allocated_minutes');
            $table->foreignId('changed_by')->constrained('users');
            $table->timestamp('changed_at');
            $table->string('reason')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_type_complexity_history');
        Schema::dropIfExists('task_type_complexities');
        Schema::dropIfExists('complexity_levels');
        Schema::dropIfExists('task_types');
    }
}; 