<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tecnico extends Model
{
    // Especificamos el nombre de la tabla en la base de datos
    protected $table = 'tecnicos';

    // Campos que se pueden asignar de forma masiva (mass assignment)
    protected $fillable = [
        'nombre_completo',
        'especialidad_id',
        'disponible',
        'usuario_id'  // Por si se relaciona con un usuario
    ];

    // Desactivamos timestamps porque la tabla 'tecnicos' no tiene created_at/updated_at
    public $timestamps = false;

    /**
     * Relación con Especialidad (un técnico pertenece a una especialidad)
     */
    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'especialidad_id');
    }

    /**
     * Relación con Incidencias (un técnico puede tener muchas incidencias)
     */
    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'tecnico_id');
    }
}