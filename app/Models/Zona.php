<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zona extends Model
{
    protected $table = 'zonas';

    protected $fillable = [
        'nombre_zona',
    ];

    public function incidencias(): HasMany
    {
        return $this->hasMany(Incidencia::class);
    }
}
