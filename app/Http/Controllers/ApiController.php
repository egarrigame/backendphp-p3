<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zona;
use App\Models\Incidencia;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    public function getEstadisticasZonas(): JsonResponse
    {
        $totalGlobal = Incidencia::count();

        if ($totalGlobal == 0) {
            return response()->json(['message' => 'No hay servicios registrados'], 200);
        }

        $zonas = Zona::all();

        $resultado = $zonas->map(function ($zona) use ($totalGlobal) {
            $totalZona = Incidencia::whereHas('comunidad', function ($query) use ($zona) {
                $query->where('zona_id', $zona->id);
            })->count();

            return [
                'nombre' => $zona->nombre,
                'total_servicios' => $totalZona,
                'porcentaje' => round(($totalZona / $totalGlobal) * 100, 2) . '%'
            ];
        });

        return response()->json($resultado);
    }
}
