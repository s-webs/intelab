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
        Schema::table('users', function (Blueprint $table) {
            $table->string('photo')->nullable();
            $table->string('phone')->nullable();
            $table->string('organisation')->nullable();
            $table->string('position')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('gender')->nullable();
            $table->string('birthday')->nullable();
            $table->string('terms')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('photo');
            $table->dropColumn('phone');
            $table->dropColumn('organization');
            $table->dropColumn('position');
            $table->dropColumn('country');
            $table->dropColumn('city');
            $table->dropColumn('gender');
            $table->dropColumn('birthday');
            $table->dropColumn('terms');
        });
    }
};
