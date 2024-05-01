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
        Schema::table('gateways', function (Blueprint $table) {
            $table->string('digitopay_uri')->nullable();
            $table->string('digitopay_cliente_id')->nullable();
            $table->string('digitopay_cliente_secret')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gateways', function (Blueprint $table) {
            $table->dropColumn('digitopay_uri');
            $table->dropColumn('digitopay_cliente_id');
            $table->dropColumn('digitopay_cliente_secret');
        });
    }
};
