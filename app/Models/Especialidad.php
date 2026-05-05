<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    // Nombre de la tabla en la base de datos
    protected $table = 'especialidades';

    // Campos que se pueden asignar masivamente
    protected $fillable = ['nombre_especialidad'];

    // La tabla no tiene timestamps (created_at, updated_at)
    public $timestamps = false;
}