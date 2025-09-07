<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reclassificacao extends Model
{
    use HasFactory;

    protected $table = 'reclassificacoes';

    protected $fillable = ['candidato_id', 'motivo', 'documento_path', 'status', 'nova_classificacao_geral', 'nova_classificacao_cota', 'aprovado_por_user_id', 'data_aprovacao'];

    public function candidato(): BelongsTo
    {
        return $this->belongsTo(Candidato::class);
    }

    public function aprovadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprovado_por_user_id');
    }
}
