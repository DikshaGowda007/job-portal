<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruiter_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            $table->string('company_name')->nullable();
            $table->string('company_logo_path')->nullable();
            $table->text('company_about')->nullable();
            $table->string('company_website')->nullable();
            $table->enum('company_size', ['1-10', '11-50', '51-200', '201-500', '500+'])->nullable();
            $table->string('industry')->nullable();
            $table->string('company_phone', 20)->nullable();

            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();

            $table->string('linkedin_url')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedTinyInteger('is_deleted')->default(0)->comment('1 - Yes, 0 - No');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruiter_profiles');
    }
};
