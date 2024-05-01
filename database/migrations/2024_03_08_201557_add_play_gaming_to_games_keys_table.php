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
        Schema::table('games_keys', function (Blueprint $table) {
            $table->string('play_gaming_hall')->nullable();
            $table->string('play_gaming_key')->nullable();
            $table->string('play_gaming_login')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games_keys', function (Blueprint $table) {
            $table->dropColumn('play_gaming_hall');
            $table->dropColumn('play_gaming_key');
            $table->dropColumn('play_gaming_login');
        });
    }
};
