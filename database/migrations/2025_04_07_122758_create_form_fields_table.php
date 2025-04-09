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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_group_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('label');
            $table->string('type'); // text, dropdown, radio, textarea, etc.
            $table->boolean('required')->default(false);
            $table->integer('display_order')->default(0);
            $table->text('options')->nullable(); // JSON field for dropdown/radio options
            $table->text('validation_rules')->nullable(); // JSON field for validation rules
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Add unique constraint to prevent duplicate fields for a task group
            $table->unique(['task_group_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
