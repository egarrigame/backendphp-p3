<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comunidad extends Model
{
    protected $table = 'comunidades';  // ← FORZAMOS EL NOMBRE CORRECTO
    
    protected $fillable = [
        'nombre',
        'direccion',
        'zona',
        'empresa_gestora_id'
    ];
    
    public function empresaGestora()
    {
        return $this->belongsTo(EmpresaGestora::class, 'empresa_gestora_id');
    }
    
    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'comunidad_id');
    }
}