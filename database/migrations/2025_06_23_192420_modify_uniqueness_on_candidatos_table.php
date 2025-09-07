<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
 public function up(): void
    {
        // Desativa temporariamente a verificação de chaves estrangeiras
        Schema::disableForeignKeyConstraints();

        // Limpa todos os registos da tabela para evitar conflitos.
        DB::table('candidatos')->truncate();

        // Reativa a verificação de chaves estrangeiras
        Schema::enableForeignKeyConstraints();

        Schema::table('candidatos', function (Blueprint $table) {
            // Adiciona a nova coluna `concurso_id`
            if (!Schema::hasColumn('candidatos', 'concurso_id')) {
                // Adiciona a coluna e a chave estrangeira numa única linha
                $table->foreignId('concurso_id')->after('id')->constrained('concursos')->onDelete('cascade');
            }

            // Remove o índice único antigo da coluna 'inscricao'
            $table->dropUnique('candidatos_inscricao_unique');

            // Adiciona o novo índice único composto
            $table->unique(['concurso_id', 'inscricao']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            // Reverte as alterações na ordem inversa
            $table->dropUnique(['concurso_id', 'inscricao']);
            $table->unique('inscricao');

            if (Schema::hasColumn('candidatos', 'concurso_id')) {
                $table->dropForeign(['concurso_id']);
                $table->dropColumn('concurso_id');
            }
        });
    }
};
