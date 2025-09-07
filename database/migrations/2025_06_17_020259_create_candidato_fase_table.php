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
        Schema::create('candidato_fase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chamamento_candidato_id')->constrained('chamamento_candidato')->onDelete('cascade');
            $table->foreignId('fase_id')->constrained('fases')->onDelete('cascade');
            $table->enum('status', ['apto', 'inapto', 'apto_condicional', 'pendente'])->default('pendente');
            $table->string('documento_path')->nullable()->comment('Documento de justificativa para status condicional');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidato_fase');
    }
};
