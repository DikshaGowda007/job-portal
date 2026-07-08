<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE job_posts DROP CONSTRAINT IF EXISTS job_posts_status_check");
        DB::statement("ALTER TABLE job_posts ALTER COLUMN status SET DEFAULT 'DRAFT'");
        DB::statement("ALTER TABLE job_posts ADD CONSTRAINT job_posts_status_check CHECK (status IN ('OPEN', 'CLOSED', 'EXPIRED', 'DRAFT'))");
    }

    public function down(): void
    {
        DB::statement("UPDATE job_posts SET status = 'OPEN' WHERE status = 'DRAFT'");
        DB::statement("ALTER TABLE job_posts DROP CONSTRAINT IF EXISTS job_posts_status_check");
        DB::statement("ALTER TABLE job_posts ALTER COLUMN status SET DEFAULT 'OPEN'");
        DB::statement("ALTER TABLE job_posts ADD CONSTRAINT job_posts_status_check CHECK (status IN ('OPEN', 'CLOSED', 'EXPIRED'))");
    }
};
