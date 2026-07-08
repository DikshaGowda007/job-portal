<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('email_template_id')->nullable();
            $table->unsignedBigInteger('email_vendor_account_id')->nullable();
            $table->string('to_email_id', 255)->nullable();
            $table->string('email_subject', 500)->nullable();
            $table->text('email_body')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('onboard_user_id')->nullable();
            $table->unsignedBigInteger('om_row_id')->nullable();
            $table->boolean('is_sent_received')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
