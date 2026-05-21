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
        Schema::create('job_seeker_education', function (Blueprint $table) {
            $table->id();

            // Link to profile
            $table->foreignId('job_seeker_profile_id')->constrained('job_seeker_profiles')->cascadeOnDelete();

            // Education details
            $table->string('degree');
            $table->string('field_of_study')->nullable();
            $table->string('institution');
            $table->string('location')->nullable();

            // Duration
            $table->year('start_year')->nullable();
            $table->year('end_year')->nullable();
            $table->boolean('is_current')->default(false);

            // Results
            $table->string('grade')->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->decimal('cgpa', 4, 2)->nullable();

            // Additional
            $table->text('description')->nullable();
            $table->json('achievements')->nullable();

            // Timestamps
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedTinyInteger('is_deleted')->default(0)->comment('1 - Yes, 0 - No');

            // Index for ordering
            $table->index(['job_seeker_profile_id', 'end_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_seeker_education');
    }
};
