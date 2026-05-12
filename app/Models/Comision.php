<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comision extends Model
{
    protected $table = 'comisiones';

    protected $fillable = [
        'gestora_id',
        'incidencia_id',
        'monto_base',
        'porcentaje_aplicado',
        'monto_comision',
        'mes',
        'pagada',
    ];

    protected $casts = [
        'mes' => 'date',
        'pagada' => 'boolean',
        'monto_base' => 'decimal:2',
        'monto_comision' => 'decimal:2',
    ];

    public function gestora(): BelongsTo
    {
        return $this->belongsTo(EmpresaGestora::class, 'gestora_id');
    }

    public function incidencia(): BelongsTo
    {
        return $this->belongsTo(Incidencia::class);
    }
}
