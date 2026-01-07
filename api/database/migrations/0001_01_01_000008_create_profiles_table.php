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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->enum('type', ['student', 'teacher', 'admin']);
            $table->string('address')->nullable();
            $table->timestamps();
            
            // Foreign key without cascade
            $table->foreign('user_id')->references('id')->on('users');
            
            // Performance indexes
            $table->unique('user_id'); 
            $table->index(['type', 'gender']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
