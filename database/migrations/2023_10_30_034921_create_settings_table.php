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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('software_name')->nullable();
            $table->string('software_description')->nullable();
            $table->string('software_favicon')->nullable();
            $table->string('software_logo_white')->nullable();
            $table->string('software_logo_black')->nullable();
            $table->string('currency_code')->default('BRL');
            $table->string('prefix')->default('R$');
            $table->string('decimal_format', 20)->default('dot');
            $table->string('currency_position', 20)->default('left');
            $table->string('storage')->default('local');
            $table->decimal('min_deposit', 20, 2)->default(20);
            $table->decimal('max_deposit', 20, 2)->default(0);
            $table->decimal('min_withdrawal', 20, 2)->default(20);
            $table->decimal('max_withdrawal', 20, 2)->default(0);


            $table->bigInteger('bonus_vip')->default(100);
            $table->boolean('activate_vip_bonus')->default(true);

            // percent
            $table->bigInteger('ngr_percent')->default(20);
            $table->bigInteger('revshare_percentage')->default(20);
            $table->bigInteger('soccer_percentage')->default(30);
            $table->bigInteger('initial_bonus')->default(100);


            $table->bigInteger('rollover')->default(1);
            $table->bigInteger('rollover_deposit')->default(1);


            /// gateway
            $table->boolean('suitpay_is_enable')->default(true);
            $table->boolean('stripe_is_enable')->default(true);
            $table->boolean('bspay_is_enable')->default(true);

            $table->tinyInteger('disable_rollover')->default(0);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
