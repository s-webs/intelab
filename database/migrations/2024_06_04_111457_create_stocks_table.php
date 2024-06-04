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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('image');
            $table->text('link')->nullable();
            $table->string('name_en')->nullable();
            $table->text('image_en')->nullable();
            $table->text('link_en')->nullable();
            $table->string('name_kz')->nullable();
            $table->text('image_kz')->nullable();
            $table->text('link_kz')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
