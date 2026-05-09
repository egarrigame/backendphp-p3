<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    // Especificamos el nombre de la tabla
    protected $table = 'servicios';
    
    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'empresa_gestora_id',
        'comunidad_id',
        'descripcion',
        'precio_base',
        'comision_aplicada',
        'estado'
    ];
    
    // Relación con la empresa gestora
    public function empresaGestora()
    {
        return $this->belongsTo(EmpresaGestora::class, 'empresa_gestora_id');
    }
    
    // Relación con la comunidad
    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class, 'comunidad_id');
    }
}