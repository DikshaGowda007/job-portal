<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shortlisted_candidates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recruiter_user_id');
            $table->unsignedBigInteger('candidate_user_id');
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamp('created_at');
            $table->foreign('recruiter_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('candidate_user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['recruiter_user_id', 'candidate_user_id'], 'shortlisted_candidates_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shortlisted_candidates');
    }
};
