<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chamamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'concurso_id',
        'numero_chamamento',
        'data_publicacao',
        'prazo_apresentacao',
    ];

    public function concurso(): BelongsTo
    {
        return $this->belongsTo(Concurso::class);
    }

    public function chamamentoCandidato(): HasMany
    {
        return $this->hasMany(ChamamentoCandidato::class);
    }
}
