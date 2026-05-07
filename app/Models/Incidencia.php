<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    // Especificamos el nombre de la tabla
    protected $table = 'incidencias';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'localizador',
        'cliente_id',
        'tecnico_id',
        'especialidad_id',
        'descripcion',
        'direccion',
        'fecha_servicio',
        'tipo_urgencia',
        'estado'
    ];

    // Desactivamos timestamps porque la tabla no tiene updated_at
    public $timestamps = false;

    // Relación: una incidencia pertenece a un cliente (usuario)
    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    // Relación: una incidencia pertenece a un técnico
    public function tecnico()
    {
        return $this->belongsTo(Tecnico::class, 'tecnico_id');
    }

    // Relación: una incidencia pertenece a una especialidad
    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'especialidad_id');
    }
}