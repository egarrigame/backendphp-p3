<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comunidad;
use App\Models\Zona;
use Illuminate\Support\Facades\Auth;
use App\Models\Incidencia;
use Illuminate\Support\Str;
use App\Models\Especialidad;

class GestoraController extends Controller
{
    public function index()
    {
        return view('gestora.dashboard');
    }

public function indexComunidades()
    {
        $gestoraId = Auth::id();
        $comunidades = Comunidad::where('gestora_id', $gestoraId)->get();

        return view('gestora.comunidades.index', compact('comunidades'));
    }

    public function create()
    {
        $zonas = \App\Models\Zona::all();
        return view('gestora.comunidades.create', compact('zonas'));
    }

    public function store(Request $request) {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'zona_id' => 'required|exists:zonas,id',
        ]);

        Comunidad::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'zona_id' => $request->zona_id,
            'gestora_id' => Auth::id(),

        ]);
        return redirect()->route('gestora.dashboard')->with('success', 'Comunidad registrada.');
    }

    public function crearIncidencia($id)
    {
        $comunidad = Comunidad::findOrFail($id);
        $especialidades = Especialidad::all();
        
        if ($comunidad->gestora_id !== Auth::id()) {
            abort(403, 'No tienes permiso para reportar incidencias en esta comunidad.');
        }

        $especialidades = Especialidad::all();

        return view('gestora.incidencias.create', compact('comunidad', 'especialidades'));
    }

    public function storeIncidencia(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'required|string',
            'tipo_urgencia' => 'required|in:Estándar,Urgente',
            'especialidad_id' => 'required|exists:especialidades,id',
            'fecha_servicio' => 'required|date|after_or_equal:today',
        ]);

        Incidencia::create([
            'localizador' => strtoupper(Str::random(8)),
            'descripcion' => $request->descripcion,
            'tipo_urgencia' => $request->tipo_urgencia,
            'especialidad_id' => $request->especialidad_id,
            'direccion' => Comunidad::find($id)->direccion,
            'comunidad_id' => $id,
            'cliente_id' => Auth::id(),
            'estado_id' => 1,
            'fecha_servicio' => $request->fecha_servicio,
        ]);

        return redirect()->route('gestora.dashboard')->with('success', 'Incidencia creada correctamente.');
    }

    public function indexComisiones()
    {
        $gestoraId = Auth::id();

        $serviciosRealizados = Incidencia::with(['comunidad', 'especialidad', 'estado'])
                                         ->where('cliente_id', $gestoraId)
                                         ->where('estado_id', 4) 
                                         ->orderBy('fecha_servicio', 'desc')
                                         ->get();

        $totalComisiones = $serviciosRealizados->sum('comision_calculada');

        return view('gestora.comisiones', compact('serviciosRealizados', 'totalComisiones'));
    }
}
