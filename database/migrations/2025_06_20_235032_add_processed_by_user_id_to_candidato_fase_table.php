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
        Schema::table('candidato_fase', function (Blueprint $table) {
            $table->foreignId('processed_by_user_id')->nullable()->after('observacoes')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidato_fase', function (Blueprint $table) {
            $table->dropForeign(['processed_by_user_id']);
            $table->dropColumn('processed_by_user_id');
        });
    }
};
