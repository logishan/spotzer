<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('description')->nullable();
                $table->integer('level')->default(1);
                $table->timestamps();
            });

            // Insert default roles
            DB::table('roles')->insert([
                [
                    'name' => 'Admin',
                    'description' => 'Administrator with full access',
                    'level' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Manager',
                    'description' => 'Department manager with elevated access',
                    'level' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Employee',
                    'description' => 'Regular employee',
                    'level' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
}; 