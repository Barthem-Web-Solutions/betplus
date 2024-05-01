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
            $table->string('pig_agent_code')->nullable();
            $table->string('pig_agent_token')->nullable();
            $table->string('pig_agent_secret')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games_keys', function (Blueprint $table) {
            $table->dropColumn('pig_agent_code');
            $table->dropColumn('pig_agent_token');
            $table->dropColumn('pig_agent_secret');
        });
    }
};
