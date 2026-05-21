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
        Schema::create('job_seeker_profiles', function (Blueprint $table) {
            $table->id();

            // Link to user
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            // Personal info
            $table->string('headline')->nullable();
            $table->text('summary')->nullable();
            $table->string('phone', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['MALE', 'FEMALE', 'OTHER', 'PREFER_NOT_TO_SAY'])->nullable();

            // Location
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('pincode', 20)->nullable();

            // Professional info
            $table->string('current_job_title')->nullable();
            $table->string('current_company')->nullable();
            $table->integer('total_experience_years')->nullable();
            $table->integer('total_experience_months')->nullable();

            // Salary expectations
            $table->decimal('expected_salary', 10, 2)->nullable();
            $table->enum('expected_salary_currency', ['INR', 'USD', 'EUR', 'GBP'])->default('INR');
            $table->decimal('current_salary', 10, 2)->nullable();
            $table->enum('current_salary_currency', ['INR', 'USD', 'EUR', 'GBP'])->default('INR');

            // Job preferences
            $table->json('preferred_job_types')->nullable();
            $table->json('preferred_work_modes')->nullable();
            $table->json('preferred_locations')->nullable();
            $table->string('notice_period')->nullable();
            $table->boolean('immediate_joiner')->default(false);

            // Skills
            $table->json('skills')->nullable();

            // Resume
            $table->string('resume_path')->nullable();
            $table->string('resume_filename')->nullable();
            $table->timestamp('resume_uploaded_at')->nullable();

            // Profile photo
            $table->string('photo_path')->nullable();

            // Social links
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('portfolio_url')->nullable();

            // Profile completeness
            $table->unsignedTinyInteger('profile_completeness')->default(0);
            $table->boolean('is_public')->default(true);
            $table->boolean('open_to_opportunities')->default(true);

            // Timestamps
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedTinyInteger('is_deleted')->default(0)->comment('1 - Yes, 0 - No');

            // Indexes
            $table->index(['city', 'state', 'country']);
            $table->index('total_experience_years');
            $table->index('open_to_opportunities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_seeker_profiles');
    }
};
