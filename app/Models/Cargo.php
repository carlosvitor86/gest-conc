<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cargo extends Model
{
    use HasFactory;

    protected $fillable = [
        'concurso_id',
        'nome',
        'local_vaga',
        'vagas_ampla_concorrencia',
        'vagas_pcd',
        'vagas_cotas',
    ];

    public function concurso(): BelongsTo
    {
        return $this->belongsTo(Concurso::class);
    }

    public function fases(): HasMany
    {
        return $this->hasMany(Fase::class);
    }

    public function candidatos(): HasMany
    {
        return $this->hasMany(Candidato::class);
    }
}
