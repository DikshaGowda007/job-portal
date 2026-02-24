<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->unsignedTinyInteger('user_type')->default(4)->comment('1 - Admin, 2 - Sub Admin, 3 - Recruiter, 4 - Job Seeker');
            $table->boolean('verified')->default(0)->comment('1 - verified, 0 - not verified');
            $table->timestamp('last_login')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->comment('1 - Active, 2 - Inactive');
            $table->unsignedTinyInteger('is_deleted')->default(0)->comment('1 - Yes, 0 - No');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
