<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmpresaGestora extends Model
{
    protected $table = 'empresas_gestoras';

    protected $fillable = [
        'nombre',
        'cif',
        'direccion',
        'telefono',
        'email',
        'password',
        'porcentaje_comision',
        'activa',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'porcentaje_comision' => 'decimal:2',
        'activa' => 'boolean',
    ];

    public function incidencias(): HasMany
    {
        return $this->hasMany(Incidencia::class, 'gestora_id');
    }

    public function comisiones(): HasMany
    {
        return $this->hasMany(Comision::class, 'gestora_id');
    }
}
