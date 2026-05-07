<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Especificamos el nombre de la tabla en la base de datos
    protected $table = 'usuarios';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
        'telefono',
    ];

    // Campos ocultos al serializar
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Conversión de tipos
    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Desactivamos timestamps porque la tabla no tiene updated_at
    public $timestamps = false;

    // Relación con incidencias
    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'cliente_id');
    }

    // Métodos para verificar roles
    public function isAdmin()
    {
        return $this->rol === 'admin';
    }

    public function isTecnico()
    {
        return $this->rol === 'tecnico';
    }

    public function isParticular()
    {
        return $this->rol === 'particular';
    }
}
