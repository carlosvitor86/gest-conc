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
        Schema::create('chamamento_candidato', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chamamento_id')->constrained('chamamentos')->onDelete('cascade');
            $table->foreignId('candidato_id')->constrained('candidatos')->onDelete('cascade');
            $table->enum('status', ['Convocado', 'Apresentou Documentação', 'Apto para Posse', 'Tomou Posse', 'Desistiu', 'Eliminado'])->default('Convocado');
            $table->timestamp('data_status')->useCurrent();
            $table->text('observacoes')->nullable();
            $table->string('documento_aptidao_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chamamento_candidato');
    }
};
