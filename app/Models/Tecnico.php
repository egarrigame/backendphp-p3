<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tecnico extends Model
{
    protected $table = 'tecnicos';

    protected $fillable = [
        'usuario_id',
        'nombre_completo',
        'especialidad_id',
        'disponible',
    ];

    protected $casts = [
        'disponible' => 'boolean',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function especialidad(): BelongsTo
    {
        return $this->belongsTo(Especialidad::class);
    }

    public function incidencias(): HasMany
    {
        return $this->hasMany(Incidencia::class, 'tecnico_id');
    }
}
