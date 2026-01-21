<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('otp_validations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('otp_code');
            $table->timestamp('expires_at');
            $table->boolean('is_used')->default(false);
            $table->enum('type', ['registration', 'password_reset', 'two_factor', 'login', 'reset_password', 'purchase'])->default('login');
            $table->tinyInteger('attempts')->default(0);
            $table->boolean('is_locked')->default(false);
            $table->string('ip_address')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_validations');
    }
};
