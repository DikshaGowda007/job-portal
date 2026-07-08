<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->enum('status', ['OPEN', 'CLOSED', 'EXPIRED', 'DRAFT'])->default('DRAFT')->change();
        });
    }

    public function down(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->enum('status', ['OPEN', 'CLOSED', 'EXPIRED'])->default('OPEN')->change();
        });
    }
};
