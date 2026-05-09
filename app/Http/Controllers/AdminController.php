<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Muestra el listado de todos los servicios creados por gestoras
     */
    public function serviciosGestoras()
    {
        // Obtener todos los servicios con información de la gestora y comunidad
        $servicios = DB::table('servicios')
            ->join('empresas_gestoras', 'servicios.empresa_gestora_id', '=', 'empresas_gestoras.id')
            ->join('comunidades', 'servicios.comunidad_id', '=', 'comunidades.id')
            ->select(
                'servicios.*',
                'empresas_gestoras.nombre as gestora_nombre',
                'empresas_gestoras.email as gestora_email',
                'comunidades.nombre as comunidad_nombre'
            )
            ->orderBy('servicios.created_at', 'desc')
            ->get();
        
        return view('admin.servicios_gestoras', compact('servicios'));
    }
    
    /**
     * Muestra el total a liquidar por cada gestora
     */
    public function liquidaciones()
    {
        // Sumar comisiones agrupadas por gestora
        $liquidaciones = DB::table('servicios')
            ->join('empresas_gestoras', 'servicios.empresa_gestora_id', '=', 'empresas_gestoras.id')
            ->select(
                'empresas_gestoras.id',
                'empresas_gestoras.nombre',
                'empresas_gestoras.email',
                'empresas_gestoras.comision_porcentaje',
                DB::raw('SUM(servicios.comision_aplicada) as total_comisiones'),
                DB::raw('COUNT(servicios.id) as total_servicios')
            )
            ->groupBy('empresas_gestoras.id', 'empresas_gestoras.nombre', 'empresas_gestoras.email', 'empresas_gestoras.comision_porcentaje')
            ->get();
        
        // Calcular total general de todas las liquidaciones
        $totalGeneral = $liquidaciones->sum('total_comisiones');
        
        return view('admin.liquidaciones', compact('liquidaciones', 'totalGeneral'));
    }
    
    /**
     * Muestra detalle de servicios de una gestora específica
     */
    public function detalleGestora($id)
    {
        $servicios = DB::table('servicios')
            ->join('comunidades', 'servicios.comunidad_id', '=', 'comunidades.id')
            ->where('servicios.empresa_gestora_id', $id)
            ->select('servicios.*', 'comunidades.nombre as comunidad_nombre')
            ->get();
        
        $gestora = DB::table('empresas_gestoras')->where('id', $id)->first();
        
        return view('admin.detalle_gestora', compact('servicios', 'gestora'));
    }
}