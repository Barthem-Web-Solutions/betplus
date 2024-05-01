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
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->string('challenge_name');
            $table->text('challenge_description');
            $table->text('challenge_rules');
            $table->string('challenge_type', 20)->default('game');
            $table->string('challenge_link')->nullable();
            $table->dateTime('challenge_start_date');
            $table->dateTime('challenge_end_date');
            $table->decimal('challenge_bonus', 20, 2)->default(0);
            $table->bigInteger('challenge_total')->default(1);
            $table->string('challenge_currency', 5);
            $table->string('challenge_provider', 50)->nullable();
            $table->string('challenge_gameid', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missions');
    }
};
