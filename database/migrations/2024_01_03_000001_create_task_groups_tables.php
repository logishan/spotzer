<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('task_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('task_group_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_group_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('can_view')->default(true);
            $table->boolean('can_edit')->default(false);
            $table->foreignId('granted_by')->constrained('users');
            $table->timestamps();
            
            $table->unique(['task_group_id', 'user_id']);
        });

        Schema::create('task_group_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_group_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['task_group_id', 'task_id']);
        });

        Schema::create('task_group_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_group_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['task_group_id', 'department_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_group_departments');
        Schema::dropIfExists('task_group_tasks');
        Schema::dropIfExists('task_group_access');
        Schema::dropIfExists('task_groups');
    }
}; 