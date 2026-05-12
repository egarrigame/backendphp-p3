<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Incidencia extends Model
{
    protected $table = 'incidencias';

    protected $fillable = [
        'localizador',
        'cliente_id',
        'tecnico_id',
        'especialidad_id',
        'estado_id',
        'zona_id',
        'gestora_id',
        'nombre_residente',
        'descripcion',
        'direccion',
        'fecha_servicio',
        'tipo_urgencia',
        'precio_base',
    ];

    protected $casts = [
        'fecha_servicio' => 'datetime',
        'precio_base' => 'decimal:2',
    ];

    public static function generarLocalizador(): string
    {
        return 'R' . strtoupper(substr(uniqid(), -6));
    }

    public function scopeFinalizadas(Builder $query): Builder
    {
        return $query->whereHas('estado', function ($q) {
            $q->where('nombre_estado', 'Finalizada');
        });
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(Tecnico::class);
    }

    public function especialidad(): BelongsTo
    {
        return $this->belongsTo(Especialidad::class);
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }

    public function zona(): BelongsTo
    {
        return $this->belongsTo(Zona::class);
    }

    public function gestora(): BelongsTo
    {
        return $this->belongsTo(EmpresaGestora::class, 'gestora_id');
    }

    public function comision(): HasOne
    {
        return $this->hasOne(Comision::class);
    }
}
