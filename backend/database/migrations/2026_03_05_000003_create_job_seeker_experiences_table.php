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
        Schema::create('job_seeker_experiences', function (Blueprint $table) {
            $table->id();

            // Link to profile
            $table->foreignId('job_seeker_profile_id')->constrained('job_seeker_profiles')->cascadeOnDelete();

            // Job details
            $table->string('job_title');
            $table->string('company_name');
            $table->enum('employment_type', ['FULL_TIME', 'PART_TIME', 'CONTRACT', 'INTERNSHIP', 'FREELANCE'])->default('FULL_TIME');
            $table->string('location')->nullable();
            $table->enum('work_mode', ['ONSITE', 'REMOTE', 'HYBRID'])->nullable();

            // Duration
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);

            // Description
            $table->text('description')->nullable();
            $table->json('responsibilities')->nullable();
            $table->json('achievements')->nullable();
            $table->json('skills_used')->nullable();

            // Timestamps
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedTinyInteger('is_deleted')->default(0)->comment('1 - Yes, 0 - No');

            // Index for ordering
            $table->index(['job_seeker_profile_id', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_seeker_experiences');
    }
};
