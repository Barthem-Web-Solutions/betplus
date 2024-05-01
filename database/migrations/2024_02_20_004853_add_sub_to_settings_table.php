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
        Schema::table('settings', function (Blueprint $table) {
            $table->bigInteger('perc_sub_lv1')->default(4);
            $table->bigInteger('perc_sub_lv2')->default(2);
            $table->bigInteger('perc_sub_lv3')->default(3);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('perc_sub_lv1');
            $table->dropColumn('perc_sub_lv2');
            $table->dropColumn('perc_sub_lv3');
        });
    }
};
