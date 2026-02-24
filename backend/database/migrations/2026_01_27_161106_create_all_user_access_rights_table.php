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
        Schema::create('all_user_access_rights', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('user_id')->default(0)->index();

            // Job Management
            $table->tinyInteger('job_view')->default(0);
            $table->tinyInteger('job_create')->default(0);
            $table->tinyInteger('job_edit')->default(0);
            $table->tinyInteger('job_delete')->default(0);
            $table->tinyInteger('job_publish')->default(0);
            $table->tinyInteger('job_close')->default(0);

            // Application Management
            $table->tinyInteger('application_view')->default(0);
            $table->tinyInteger('application_shortlist')->default(0);
            $table->tinyInteger('application_reject')->default(0);
            $table->tinyInteger('application_download_resume')->default(0);

            // Company / Recruiter
            $table->tinyInteger('company_profile_view')->default(0);
            $table->tinyInteger('company_profile_edit')->default(0);
            $table->tinyInteger('recruiter_manage')->default(0);

            // Admin
            $table->tinyInteger('category_manage')->default(0);
            $table->tinyInteger('user_manage')->default(0);
            $table->tinyInteger('role_manage')->default(0);

            $table->dateTime('created_date')->useCurrent();
            $table->dateTime('modified_date')->useCurrent()->useCurrentOnUpdate();

            $table->unsignedTinyInteger('status')->default(1)->comment('1 - Active, 2 - Inactive');
            $table->unsignedTinyInteger('is_deleted')->default(0)->comment('1 - Yes, 0 - No');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('all_user_access_rights');
    }
};
