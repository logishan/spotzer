<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('parent_department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('user_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->boolean('is_department_head')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'department_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_departments');
        Schema::dropIfExists('departments');
    }
}; 