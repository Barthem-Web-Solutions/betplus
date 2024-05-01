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
            $table->string('games2_agent_code')->nullable();
            $table->string('games2_agent_token')->nullable();
            $table->string('games2_agent_secret_key')->nullable();
            $table->string('games2_api_endpoint')->default('api.games2api.xyz');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games_keys', function (Blueprint $table) {
            $table->dropColumn('games2_agent_code');
            $table->dropColumn('games2_agent_token');
            $table->dropColumn('games2_agent_secret_key');
            $table->dropColumn('games2_api_endpoint');
        });
    }
};
