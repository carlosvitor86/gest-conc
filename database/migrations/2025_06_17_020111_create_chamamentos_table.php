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
        Schema::create('chamamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concurso_id')->constrained('concursos')->onDelete('cascade');
            $table->string('numero_chamamento');
            $table->date('data_publicacao');
            $table->date('prazo_apresentacao');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chamamentos');
    }
};
