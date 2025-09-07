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
        Schema::table('candidatos', function (Blueprint $table) {
            // Adiciona um índice único para a combinação de cargo e classificação geral.
            $table->unique(['cargo_id', 'classificacao_geral']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            // Remove o índice ao reverter a migração.
            $table->dropUnique(['cargo_id', 'classificacao_geral']);
        });
    }
};
