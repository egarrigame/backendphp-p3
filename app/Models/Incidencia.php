<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    use HasFactory;

    protected $table = 'incidencias';

    public $timestamps = false;

    protected $fillable = [
        'localizador',
        'cliente_id',
        'tecnico_id',
        'especialidad_id',
        'estado_id',
        'fecha_servicio',
        'tipo_urgencia',
        'direccion',
        'descripcion',
        'comunidad_id',
        'precio_base',
        'porcentaje_comision',
        'comision_calculada'
    ];

    public function cliente() {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function tecnico() {
        return $this->belongsTo(Tecnico::class, 'tecnico_id');
    }

    public function especialidad() {
        return $this->belongsTo(Especialidad::class, 'especialidad_id');
    }

    public function estado() {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function comunidad() {
        return $this->belongsTo(Comunidad::class, 'comunidad_id');
    }

    protected static function booted()
    {
        static::creating(function ($incidencia) {
            $precio = ($incidencia->tipo_urgencia === 'Urgente') ? 100.00 : 60.00;
            $incidencia->precio_base = $precio;

            $porcentaje = 0;
            if ($incidencia->cliente_id) {
                $usuario = User::find($incidencia->cliente_id);
                if ($usuario && $usuario->comision_pactada) {
                    $porcentaje = $usuario->comision_pactada;
                }
            }

            $incidencia->porcentaje_comision = $porcentaje;
            $incidencia->comision_calculada = $precio * ($porcentaje / 100);
        });
    }
}
