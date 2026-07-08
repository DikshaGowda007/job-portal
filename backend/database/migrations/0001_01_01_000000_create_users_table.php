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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // $table->string('first_name')->nullable();
            // $table->string('last_name')->nullable();
            // $table->string('email')->nullable();
            // $table->string('password')->nullable();

            // $table->unsignedTinyInteger('user_type')->default(4);
            // $table->boolean('verified')->default(false);

            // $table->timestamp('last_login')->nullable();

            // $table->timestamp('created_at')->nullable();
            // $table->timestamp('updated_at')->nullable();

            // $table->unsignedTinyInteger('status')->default(1);
            // $table->unsignedTinyInteger('is_deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};