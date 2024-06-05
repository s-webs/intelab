<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('image')->default('/assets/images/no_image.png');
            $table->string('language');
            $table->string('name');
            $table->integer('price')->default(0);
            $table->integer('old_price')->default(0);
            $table->text('description');
            $table->longText('content');
            $table->integer('duration');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('status')->default(0);
            $table->string('type');
            $table->integer('rating')->default(0);
            $table->boolean('hidden')->default(0);
            $table->string('university')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
