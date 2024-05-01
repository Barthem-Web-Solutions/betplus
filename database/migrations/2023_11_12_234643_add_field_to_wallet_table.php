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
        Schema::table('wallets', function (Blueprint $table) {
            $table->decimal('total_bet', 20, 2)->default(0);
            $table->bigInteger('total_won')->default(0);
            $table->bigInteger('total_lose')->default(0);
            $table->bigInteger('last_won')->default(0);
            $table->bigInteger('last_lose')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropColumn('total_bet');
            $table->dropColumn('total_won');
            $table->dropColumn('total_lose');
            $table->dropColumn('last_won');
            $table->dropColumn('last_lose');
        });
    }
};
