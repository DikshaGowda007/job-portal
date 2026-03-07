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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();

            // Job seeker who applied
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Job being applied to
            $table->foreignId('job_post_id')->constrained('job_posts')->cascadeOnDelete();

            // Application details
            $table->string('resume_path')->nullable();
            $table->text('cover_letter')->nullable();
            $table->decimal('expected_salary', 10, 2)->nullable();
            $table->enum('expected_salary_currency', ['INR', 'USD', 'EUR', 'GBP'])->default('INR');
            $table->string('notice_period')->nullable();
            $table->integer('experience_years')->nullable();

            // Application status
            $table->enum('status', ['PENDING', 'REVIEWED', 'SHORTLISTED', 'INTERVIEW', 'OFFERED', 'HIRED', 'REJECTED', 'WITHDRAWN'])
                ->default('PENDING');

            // Recruiter notes (internal)
            $table->text('recruiter_notes')->nullable();
            $table->integer('reviewed_by_user_id')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            // Prevent duplicate applications
            $table->unique(['user_id', 'job_post_id']);

            // Indexes for performance
            $table->index(['job_post_id', 'status']);
            $table->index(['user_id', 'status']);

            // Timestamps
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedTinyInteger('is_deleted')->default(0)->comment('1 - Yes, 0 - No');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
