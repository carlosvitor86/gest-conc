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
        Schema::create('concursos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->year('ano');
            $table->string('orgao');
            $table->date('data_homologacao');
            $table->string('banca_organizadora');
            $table->enum('status', ['Ativo', 'Concluído', 'Suspenso'])->default('Ativo');
            $table->string('edital_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concursos');
    }
};
