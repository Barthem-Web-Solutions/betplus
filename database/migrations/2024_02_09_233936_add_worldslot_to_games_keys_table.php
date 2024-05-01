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
            $table->string('worldslot_agent_code')->nullable();
            $table->string('worldslot_agent_token')->nullable();
            $table->string('worldslot_agent_secret_key')->nullable();
            $table->string('worldslot_api_endpoint')->default('https://api.worldslotgame.com/api/v2/');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games_keys', function (Blueprint $table) {
            $table->dropColumn('worldslot_agent_code');
            $table->dropColumn('worldslot_agent_token');
            $table->dropColumn('worldslot_agent_secret_key');
            $table->dropColumn('worldslot_api_endpoint');
        });
    }
};
