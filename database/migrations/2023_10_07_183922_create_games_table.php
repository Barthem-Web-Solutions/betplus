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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id')->index();
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
            $table->string('game_server_url')->nullable();
            $table->string('game_id');
            $table->string('game_name');
            $table->string('game_code')->unique();
            $table->string('game_type')->nullable();
            $table->string('description')->nullable();
            $table->string('cover');
            $table->string('status');
            $table->string('technology')->nullable();
            $table->tinyInteger('has_lobby')->default(0);
            $table->tinyInteger('is_mobile')->default(0);
            $table->tinyInteger('has_freespins')->default(0);
            $table->tinyInteger('has_tables')->default(0);
            $table->tinyInteger('only_demo')->default(0);
            $table->bigInteger('rtp')->comment('Controle de RTP em porcentagem');
            $table->string('distribution')->comment('O nome do provedor');
            $table->bigInteger('views')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('show_home')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
