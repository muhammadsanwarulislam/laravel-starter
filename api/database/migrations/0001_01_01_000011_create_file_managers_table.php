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
        Schema::create('file_managers', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->uuid('uuid')->unique();
        $table->string('name');
        $table->string('file');
        $table->string('type', 50);
        $table->string('size', 20);
        $table->string('path');
        $table->timestamps();
        
        // Foreign key without cascade
        $table->foreign('user_id')->references('id')->on('users');
        
        // Performance indexes
        $table->index(['user_id', 'created_at']);
        $table->index('type');
        $table->index(['type', 'created_at']);
        $table->index('uuid');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_managers');
    }
};
