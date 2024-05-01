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
            $table->longText('custom_header');
            $table->longText('custom_body');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_layouts', function (Blueprint $table) {
            $table->dropColumn('custom_header');
            $table->dropColumn('custom_body');
        });
    }
};
