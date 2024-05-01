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
        Schema::table('users', function (Blueprint $table) {
            $table->string('oauth_id')->nullable();
            $table->string('oauth_type')->nullable();
            $table->string('avatar')->nullable();
            $table->string('last_name')->nullable();
            $table->string('cpf', 20)->nullable();
            $table->string('phone', 30)->nullable();
            $table->tinyInteger('logged_in')->default(0);
            $table->tinyInteger('banned')->default(0);
            $table->integer('inviter')->nullable();
            $table->string('inviter_code', 25)->nullable();
            $table->bigInteger('affiliate_revenue_share')->default(2);
            $table->decimal('affiliate_cpa', 20, 2)->default(10);
            $table->decimal('affiliate_baseline', 20, 2)->default(40);
            $table->tinyInteger('is_demo_agent')->default(0);
            $table->string('status', 50)->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('oauth_id');
            $table->dropColumn('oauth_type');
            $table->dropColumn('avatar');
            $table->dropColumn('last_name');
            $table->dropColumn('cpf');
            $table->dropColumn('phone');
            $table->dropColumn('logged_in');
            $table->dropColumn('banned');
            $table->dropColumn('inviter');
            $table->dropColumn('affiliate_revenue_share');
            $table->dropColumn('affiliate_cpa');
            $table->dropColumn('affiliate_baseline');
            $table->dropColumn('is_agent');
            $table->dropColumn('status');
        });
    }
};
