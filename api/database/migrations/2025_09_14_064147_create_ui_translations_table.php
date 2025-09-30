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
        Schema::create('ui_translations', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('*');
            $table->string('key')->index();
            $table->text('value')->nullable();
            $table->unsignedBigInteger('language_id'); 
            $table->timestamps();

            $table->unique(['group', 'key', 'language_id'], 'ui_translation_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ui_translations');
    }
};
