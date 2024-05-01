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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('currency', 20);
            $table->string('symbol', 5);
            $table->decimal('balance', 20, 2)->default(0);
            $table->decimal('balance_withdrawal', 20, 2)->default(0);
            $table->decimal('balance_bonus_rollover', 20, 2)->default(0);
            $table->decimal('balance_deposit_rollover', 20, 2)->default(0);
            $table->decimal('balance_bonus', 20, 2)->default(0);
            $table->decimal('balance_cryptocurrency', 20, 8)->default(0);
            $table->decimal('balance_demo', 20, 8)->default(0);
            $table->decimal('refer_rewards', 20, 2)->default(0);
            $table->boolean('hide_balance')->default(false);
            $table->boolean('active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
