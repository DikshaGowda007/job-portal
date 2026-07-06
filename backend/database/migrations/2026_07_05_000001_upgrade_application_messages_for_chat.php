<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_messages', function (Blueprint $table) {
            $table->timestamp('read_at')->nullable()->after('message');
            $table->timestamp('updated_at')->nullable()->after('created_at');
            $table->unsignedTinyInteger('is_deleted')->default(0)->comment('1 - Yes, 0 - No')->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('application_messages', function (Blueprint $table) {
            $table->dropColumn(['read_at', 'updated_at', 'is_deleted']);
        });
    }
};
