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
        Schema::create('ggr_games_world_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('provider');
            $table->string('game');
            $table->decimal('balance_bet', 20, 2)->default(0);
            $table->decimal('balance_win', 20, 2)->default(0);
            $table->string('currency', 50)->default('BRL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ggr_games_world_slots');
    }
};
