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
        Schema::create('matching_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('step_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->json('question');
            $table->json('answers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matching_tests');
    }
};
