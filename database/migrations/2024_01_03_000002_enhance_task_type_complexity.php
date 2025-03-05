<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('task_type_complexities', function (Blueprint $table) {
            $table->softDeletes();
            $table->timestamp('last_active_at')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }

    public function down()
    {
        Schema::table('task_type_complexities', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('last_active_at');
            $table->dropColumn('is_active');
        });
    }
}; 