<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fase extends Model
{
    use HasFactory;

    protected $fillable = [
        'cargo_id',
        'nome',
        'ordem',
    ];

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }
}
