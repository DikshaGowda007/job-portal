<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();

            // Who created the job (Admin/SubAdmin/Employer)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('modified_by_user_id')->nullable();

            // Basic job info
            $table->string('company_name');
            $table->string('title');
            $table->longText('job_description'); // full paragraph text
            $table->string('location');

            // Salary range
            $table->decimal('salary', 10, 2)->nullable();
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->enum('salary_currency', ['INR', 'USD'])->nullable();
            $table->enum('salary_type', ['monthly', 'yearly'])->nullable();

            // Category relation
            $table->foreignId('job_category_id')
                ->nullable()
                ->constrained('job_categories')
                ->nullOnDelete();

            // Job type and work nature
            $table->enum('work_mode', ['onsite', 'remote', 'hybrid'])
                ->default('onsite');
            $table->enum('job_type', ['FULL_TIME', 'PART_TIME', 'REMOTE', 'INTERNSHIP'])
                ->default('FULL_TIME');

            // Roles & responsibilities
            $table->json('roles_responsibility')->nullable();

            // Experience & education
            $table->enum('experience_level', ['FRESHER', 'JUNIOR', 'MID', 'SENIOR', 'TEAM_LEAD'])
                ->default('FRESHER');
            // Experience range (years)
            $table->integer('experience_min')->nullable();
            $table->integer('experience_max')->nullable();
            $table->string('education')->nullable(); // e.g., B.Tech, MBA


            // Skills and meta
            $table->json('skills')->nullable(); // e.g., ["PHP", "React", "Laravel"]

            // Job lifecycle
            $table->enum('status', ['OPEN', 'CLOSED', 'EXPIRED'])->default('OPEN');
            $table->timestamp('expires_at')->nullable();

            // Openings count
            $table->integer('openings_count')->default(1);

            // Indexes for performance
            $table->index(['title', 'location', 'status', 'job_type', 'experience_level']);

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
        Schema::dropIfExists('job_posts');
    }
};
