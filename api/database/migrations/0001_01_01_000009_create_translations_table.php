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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('translatable_type');
            $table->unsignedBigInteger('translatable_id');
            $table->unsignedBigInteger('language_id');
            $table->string('attribute');
            $table->text('value');
            $table->timestamps();
            
            // Foreign key
            $table->foreign('language_id')->references('id')->on('languages');
            
            // Performance indexes
            $table->unique([
                'translatable_type', 
                'translatable_id', 
                'language_id', 
                'attribute'
            ], 'translation_unique');
            
            $table->index(['translatable_type', 'translatable_id']);
            $table->index('language_id');
            $table->index(['translatable_type', 'attribute']);
            $table->index(['language_id', 'attribute']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
