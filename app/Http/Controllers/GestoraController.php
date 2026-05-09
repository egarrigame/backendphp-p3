<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Servicio;
use App\Models\Comunidad;
use Illuminate\Http\Request;

class GestoraController extends Controller
{
    // ID fijo de la gestora para pruebas (sin usar el modelo)
    private function getGestoraId()
    {
        return 1;
    }
    
    public function dashboard()
    {
        $gestoraId = $this->getGestoraId();
        
        // Usar DB facade directamente
        $totalComisiones = DB::table('servicios')
            ->where('empresa_gestora_id', $gestoraId)
            ->sum('comision_aplicada');
            
        $totalServicios = DB::table('servicios')
            ->where('empresa_gestora_id', $gestoraId)
            ->count();
        
        return view('gestora.dashboard', compact('totalComisiones', 'totalServicios'));
    }
    
    public function servicios()
    {
        $servicios = DB::table('servicios')
            ->where('empresa_gestora_id', $this->getGestoraId())
            ->get();
            
        return view('gestora.servicios', compact('servicios'));
    }
    
    public function serviciosCreate()
    {
        $comunidades = DB::table('comunidades')
            ->where('empresa_gestora_id', $this->getGestoraId())
            ->get();
            
        return view('gestora.servicios_create', compact('comunidades'));
    }
    
    public function serviciosStore(Request $request)
    {
        $request->validate([
            'comunidad_id' => 'required|exists:comunidades,id',
            'descripcion' => 'required|string',
            'precio_base' => 'required|numeric|min:0',
        ]);
        
        $gestoraId = $this->getGestoraId();
        
        // Comisión fija del 5%
        $comision = $request->precio_base * 0.05;
        
        DB::table('servicios')->insert([
            'empresa_gestora_id' => $gestoraId,
            'comunidad_id' => $request->comunidad_id,
            'descripcion' => $request->descripcion,
            'precio_base' => $request->precio_base,
            'comision_aplicada' => $comision,
            'estado' => 'pendiente',
            'created_at' => now(),
        ]);
        
        return redirect()->route('gestora.servicios.index')
            ->with('success', 'Servicio creado correctamente');
    }
    
    public function comisiones()
    {
        $gestoraId = $this->getGestoraId();
        
        $comisionesPorMes = DB::table('servicios')
            ->where('empresa_gestora_id', $gestoraId)
            ->selectRaw('YEAR(created_at) as año, MONTH(created_at) as mes, SUM(comision_aplicada) as total')
            ->groupBy('año', 'mes')
            ->get();
            
        return view('gestora.comisiones', compact('comisionesPorMes'));
    }
}