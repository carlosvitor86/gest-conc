<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidatoFase extends Pivot
{
    public $incrementing = true;
    protected $table = 'candidato_fase';

    protected $fillable = ['chamamento_candidato_id', 'fase_id', 'status', 'documento_path', 'observacoes', 'processed_by_user_id'];

    /**
     * O registo da fase pertence a uma convocação específica de um candidato.
     * Chaves explícitas adicionadas para corrigir o erro.
     */
    public function chamamentoCandidato(): BelongsTo
    {
        return $this->belongsTo(ChamamentoCandidato::class, 'chamamento_candidato_id', 'id');
    }

    /**
     * O registo da fase pertence a uma fase do cargo.
     */
    public function fase(): BelongsTo
    {
        return $this->belongsTo(Fase::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by_user_id');
    }
}
