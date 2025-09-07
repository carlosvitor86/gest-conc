<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChamamentoCandidato extends Pivot
{
    public $incrementing = true;
    protected $table = 'chamamento_candidato';
    
    protected $fillable = [
        'chamamento_id',
        'candidato_id',
        'status',
        'data_status',
        'observacoes',
        'documento_aptidao_path',
    ];

    public function chamamento(): BelongsTo
    {
        return $this->belongsTo(Chamamento::class);
    }

    public function candidato(): BelongsTo
    {
        return $this->belongsTo(Candidato::class);
    }

    /**
     * Uma convocação de candidato tem o registo de muitas fases.
     * Chaves explícitas adicionadas para corrigir o erro.
     */
    public function fases(): HasMany
    {
        return $this->hasMany(CandidatoFase::class, 'chamamento_candidato_id', 'id');
    }
}
