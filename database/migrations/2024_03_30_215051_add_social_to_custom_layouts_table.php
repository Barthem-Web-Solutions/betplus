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
        Schema::table('custom_layouts', function (Blueprint $table) {
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('telegram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('whastapp')->nullable();
            $table->string('youtube')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_layouts', function (Blueprint $table) {
            $table->dropColumn('instagram');
            $table->dropColumn('facebook');
            $table->dropColumn('telegram');
            $table->dropColumn('twitter');
            $table->dropColumn('whastapp');
            $table->dropColumn('youtube');
        });
    }
};
