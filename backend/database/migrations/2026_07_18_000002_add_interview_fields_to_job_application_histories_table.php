<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_application_histories', function (Blueprint $table) {
            $table->dateTime('interview_scheduled_at')->nullable()->after('notes');
            $table->string('interview_location', 500)->nullable()->after('interview_scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::table('job_application_histories', function (Blueprint $table) {
            $table->dropColumn(['interview_scheduled_at', 'interview_location']);
        });
    }
};
