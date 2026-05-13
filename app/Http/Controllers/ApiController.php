<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\Incidencia;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    /**
     * GET /api/servicios/zonas
     *
     * Returns JSON array with finalized incidencias grouped by zona,
     * including nombre_zona, total_servicios, and porcentaje.
     */
    public function serviciosPorZona(): JsonResponse
    {
        $estadoFinalizada = Estado::where('nombre_estado', 'Finalizada')->first();

        if (!$estadoFinalizada) {
            return response()->json([], 200);
        }

        $total = Incidencia::where('estado_id', $estadoFinalizada->id)
            ->whereNotNull('zona_id')
            ->count();

        if ($total === 0) {
            return response()->json([], 200);
        }

        $zonas = Incidencia::select('zonas.nombre_zona', DB::raw('COUNT(incidencias.id) as total_servicios'))
            ->join('zonas', 'incidencias.zona_id', '=', 'zonas.id')
            ->where('incidencias.estado_id', $estadoFinalizada->id)
            ->whereNotNull('incidencias.zona_id')
            ->groupBy('incidencias.zona_id', 'zonas.nombre_zona')
            ->orderBy('zonas.nombre_zona', 'asc')
            ->get();

        $data = $zonas->map(function ($zona) use ($total) {
            return [
                'nombre_zona' => $zona->nombre_zona,
                'total_servicios' => (int) $zona->total_servicios,
                'porcentaje' => round(($zona->total_servicios / $total) * 100, 2),
            ];
        });

        return response()->json($data->values()->toArray(), 200);
    }
}
