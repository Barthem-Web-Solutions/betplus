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
        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign(['provider_id']);  // Remover a chave estrangeira existente
            $table->dropColumn('provider_id');  // Remover a coluna existente
            $table->unsignedBigInteger('provider_id')->index();  // Adicionar a coluna com o tipo correto
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');  // Recriar a chave estrangeira
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            //
        });
    }
};
