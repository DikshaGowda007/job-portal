<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_template', function (Blueprint $table) {
            $table->id();
            $table->string('template_code', 255)->unique();
            $table->string('template_name', 255)->nullable();
            $table->text('content')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('is_deleted')->default(0);
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_template');
    }
};
