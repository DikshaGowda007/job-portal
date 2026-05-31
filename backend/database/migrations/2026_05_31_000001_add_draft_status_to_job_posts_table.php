<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE job_posts MODIFY COLUMN status ENUM('OPEN','CLOSED','EXPIRED','DRAFT') NOT NULL DEFAULT 'DRAFT'");
    }

    public function down(): void
    {
        DB::statement("UPDATE job_posts SET status = 'OPEN' WHERE status = 'DRAFT'");
        DB::statement("ALTER TABLE job_posts MODIFY COLUMN status ENUM('OPEN','CLOSED','EXPIRED') NOT NULL DEFAULT 'OPEN'");
    }
};
