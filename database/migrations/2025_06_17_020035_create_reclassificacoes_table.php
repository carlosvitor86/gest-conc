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
        Schema::create('reclassificacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidato_id')->constrained('candidatos')->onDelete('cascade');
            $table->text('motivo');
            $table->string('documento_path')->nullable();
            $table->enum('status', ['solicitado', 'aprovado', 'rejeitado'])->default('solicitado');
            $table->integer('nova_classificacao_geral')->nullable();
            $table->integer('nova_classificacao_cota')->nullable();
            $table->foreignId('aprovado_por_user_id')->nullable()->constrained('users');
            $table->timestamp('data_aprovacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reclassificacoes');
    }
};
