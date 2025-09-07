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
      Schema::create('candidatos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->constrained('cargos');
            $table->string('inscricao')->unique();
            $table->string('nome_completo');
            $table->decimal('nota_final', 8, 3);
            $table->integer('classificacao_geral');
            $table->integer('classificacao_cota')->nullable();
            $table->enum('tipo_vaga', ['PCD', 'Cotas', 'Ampla_concorrencia'])->default('Ampla_concorrencia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidatos');
    }
};
