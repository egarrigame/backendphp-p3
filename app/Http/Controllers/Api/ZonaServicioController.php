<?php

/**
 * CONTROLADOR PARA LA API DE SERVICIOS POR ZONA
 * 
 * Este controlador gestiona el endpoint /api/servicios/zonas
 * Devuelve un JSON con estadísticas agrupadas por zona geográfica
 * 
 * Ruta: GET /api/servicios/zonas
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ZonaServicioController extends Controller
{
    /**
     * Devuelve un array JSON con el resumen de servicios por zona
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * Respuesta ejemplo:
     * [
     *   {
     *     "zona": "Centro",
     *     "total_servicios": 10,
     *     "porcentaje": 40.00
     *   },
     *   {
     *     "zona": "Norte",
     *     "total_servicios": 8,
     *     "porcentaje": 32.00
     *   }
     * ]
     */
    public function zonas()
    {
        // PASO 1: Obtener el total global de servicios
        $totalGlobal = DB::table('servicios')->count();
        
        // Si no hay ningún servicio, devolvemos un array vacío
        if ($totalGlobal == 0) {
            return response()->json([
                'message' => 'No hay servicios registrados',
                'data' => []
            ], 200);
        }
        
        // PASO 2: Consultar servicios agrupados por zona
        // Hacemos un JOIN entre servicios y comunidades para obtener la zona
        $serviciosPorZona = DB::table('servicios')
            ->join('comunidades', 'servicios.comunidad_id', '=', 'comunidades.id')
            ->select(
                'comunidades.zona',
                DB::raw('COUNT(servicios.id) as total_servicios')
            )
            ->whereNotNull('comunidades.zona')  // Solo comunidades con zona asignada
            ->groupBy('comunidades.zona')
            ->get();
        
        // PASO 3: Construir el array de respuesta con los porcentajes calculados
        $resultado = [];
        
        foreach ($serviciosPorZona as $item) {
            // Calcular porcentaje respecto al total global
            $porcentaje = ($item->total_servicios / $totalGlobal) * 100;
            
            $resultado[] = [
                'zona' => $item->zona,
                'total_servicios' => $item->total_servicios,
                'porcentaje' => round($porcentaje, 2)  // Redondeamos a 2 decimales
            ];
        }
        
        // PASO 4: Devolver la respuesta en formato JSON
        return response()->json($resultado, 200);
    }
}
