<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_alerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('keyword')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('job_category_id')->nullable();
            $table->string('job_type')->nullable();
            $table->string('work_mode')->nullable();
            $table->string('experience_level')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('job_category_id')->references('id')->on('job_categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_alerts');
    }
};
