<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model
{
    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
        'telefono',
    ];

    protected $hidden = [
        'password',
    ];

    public function incidencias(): HasMany
    {
        return $this->hasMany(Incidencia::class, 'cliente_id');
    }

    public function tecnico(): HasOne
    {
        return $this->hasOne(Tecnico::class, 'usuario_id');
    }
}
