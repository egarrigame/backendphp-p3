<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comunidad extends Model
{
    use HasFactory;

    protected $table = 'comunidades';
    protected $fillable = [
        'nombre',
        'direccion',
        'zona_id',
        'gestora_id',
    ];

    public function zona() { // una comunidad pertenece a una zona
        return $this->belongsTo(Zona::class);
    }

    public function gestora() { // una comunidad pertenece a una gestora (usuario)
        return $this->belongsTo(User::class, 'gestora_id');
    }
}
