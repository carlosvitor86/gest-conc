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
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concurso_id')->constrained('concursos')->onDelete('cascade');
            $table->string('nome');
            $table->string('local_vaga');
            $table->integer('vagas_ampla_concorrencia')->default(0);
            $table->integer('vagas_pcd')->default(0);
            $table->integer('vagas_cotas')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargos');
    }
};
