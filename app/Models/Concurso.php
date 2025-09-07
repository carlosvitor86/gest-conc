<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Caminho correto

class Concurso extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'ano',
        'orgao',
        'data_homologacao',
        'banca_organizadora',
        'status',
        'edital_path',
    ];

    public function cargos(): HasMany // Tipo de retorno correto
    {
        return $this->hasMany(Cargo::class);
    }

    public function chamamentos(): HasMany // Tipo de retorno correto
    {
        return $this->hasMany(Chamamento::class);
    }
}
