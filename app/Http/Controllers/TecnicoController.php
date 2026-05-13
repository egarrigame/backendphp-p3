<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Incidencia;
use Illuminate\Support\Facades\Auth;

class TecnicoController extends Controller
{
    public function index()
    {
        $usuarioLogueadoId = Auth::id();


        $perfilTecnico = \App\Models\Tecnico::where('usuario_id', $usuarioLogueadoId)->first();


        if (!$perfilTecnico) {
            return view('tecnico.dashboard', ['incidencias' => []]);
        }
        $incidencias = Incidencia::with(['cliente', 'especialidad', 'comunidad', 'estado'])
                                ->where('tecnico_id', $perfilTecnico->id) 
                                ->whereIn('estado_id', [2, 3]) 
                                ->orderBy('fecha_servicio', 'asc')
                                ->get();

        return view('tecnico.dashboard', compact('incidencias'));
    }

    public function completarServicio($id)
    {
        $perfilTecnico = \App\Models\Tecnico::where('usuario_id', Auth::id())->firstOrFail();

        $incidencia = Incidencia::where('id', $id)
                                ->where('tecnico_id', $perfilTecnico->id)
                                ->firstOrFail();

        $incidencia->estado_id = 4;
        $incidencia->save();

        return redirect()->route('tecnico.dashboard')->with('success', '¡Servicio marcado como completado!');
    }
}
