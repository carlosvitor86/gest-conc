<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidato extends Model
{
    use HasFactory;

    protected $fillable = [
        'concurso_id',
        'cargo_id',
        'inscricao',
        'nome_completo',
        'nota_final',
        'classificacao_geral',
        'classificacao_cota',
        'tipo_vaga',
    ];
    
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    public function reclassificacoes(): HasMany
    {
        return $this->hasMany(Reclassificacao::class);
    }

    public function chamamentoCandidato(): HasMany
    {
        return $this->hasMany(ChamamentoCandidato::class);
    }
}
