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
            $table->string('evergame_agent_code')->nullable();
            $table->string('evergame_agent_token')->nullable();
            $table->string('evergame_api_endpoint')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games_keys', function (Blueprint $table) {
            $table->dropColumn('evergame_agent_code');
            $table->dropColumn('evergame_agent_token');
            $table->dropColumn('evergame_api_endpoint');
        });
    }
};
