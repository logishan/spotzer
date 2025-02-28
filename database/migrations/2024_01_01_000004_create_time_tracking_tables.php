<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->boolean('is_manual_entry')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('time_entry_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('time_entry_id')->constrained()->onDelete('cascade');
            $table->foreignId('approved_by')->constrained('users');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('time_entry_approvals');
        Schema::dropIfExists('time_entries');
    }
}; 