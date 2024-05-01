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
            $table->string('mp_client_id')->nullable();
            $table->string('mp_client_secret')->nullable();
            $table->string('mp_public_key')->nullable();
            $table->string('mp_access_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gateways', function (Blueprint $table) {
            $table->dropColumn('mp_client_id');
            $table->dropColumn('mp_client_secret');
            $table->dropColumn('mp_public_key');
            $table->dropColumn('mp_access_token');
        });
    }
};
