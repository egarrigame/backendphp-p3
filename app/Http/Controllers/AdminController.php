<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Tecnico;
use App\Models\Especialidad;
use App\Models\Incidencia;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    // GESTORAS ---------------------------------------------------
    public function indexGestoras()
    {
        $gestoras = User::where('rol', 'gestora')->get();

        foreach ($gestoras as $gestora) {
            $gestora->deuda_total = \App\Models\Incidencia::where('cliente_id', $gestora->id)
                                        ->where('estado_id', 4) // Solo cobradas
                                        ->sum('comision_calculada');
        }
        return view('admin.gestoras.index', compact('gestoras'));
    }

    public function createGestora()
    {
        return view('admin.gestoras.crear-gestora');
    }

    public function storeGestora(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6',
            'telefono' => 'required|string|max:20',
            'comision_pactada' => 'required|numeric|min:0|max:100',
        ]);

        User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'rol' => 'gestora',
            'comision_pactada' => $request->comision_pactada,
        ]);

        return redirect()->route('admin.gestoras.index')->with('success', '¡Gestora dada de alta con éxito!');
    }

    public function showLiquidacion($id)
    {
        $gestora = User::findOrFail($id);

        $servicios = \App\Models\Incidencia::with(['comunidad', 'especialidad'])
                                ->where('cliente_id', $gestora->id)
                                ->where('estado_id', 4)
                                ->orderBy('fecha_servicio', 'desc')
                                ->get();
                                
        $totalDeuda = $servicios->sum('comision_calculada');

        return view('admin.gestoras.liquidacion', compact('gestora', 'servicios', 'totalDeuda'));
    }

    // TECNICOS ---------------------------------------------------
    public function indexTecnicos()
    {
        $tecnicos = User::where('rol', 'tecnico')->get();
        return view('admin.tecnicos.index', compact('tecnicos'));
    }

    public function createTecnico()
    {
        $especialidades = Especialidad::all();
        return view('admin.tecnicos.crear-tecnico', compact('especialidades'));
    }

    public function storeTecnico(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6',
            'telefono' => 'required|string|max:20',
            'especialidad_id' => 'required|exists:especialidades,id',
        ]);

        $user = User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'rol' => 'tecnico',
        ]);

        Tecnico::create([
            'usuario_id' => $user->id,
            'nombre_completo' => $request->nombre,
            'especialidad_id' => $request->especialidad_id,
            'disponible' => 1,
        ]);

        return redirect()->route('admin.tecnicos.index')->with('success', 'Técnico dado de alta con éxito en ambas tablas.');
    }

    public function toggleTecnico($id)
    {
        $tecnico = Tecnico::where('usuario_id', $id)->firstOrFail();
        
        $tecnico->disponible = !$tecnico->disponible;
        $tecnico->save();

        return redirect()->route('admin.tecnicos.index')->with('success', 'Estado del técnico actualizado.');
    }

    // SERVICIOS ---------------------------------------------------
    public function indexServicios()
    {
        $incidencias = Incidencia::with(['cliente', 'tecnico.usuario', 'estado', 'especialidad'])
                                 ->orderBy('fecha_servicio', 'desc')
                                 ->get();
                                 
        return view('admin.servicios.index', compact('incidencias'));
    }

    public function showServicio($id)
    {

        $incidencia = Incidencia::with(['cliente', 'tecnico', 'estado', 'especialidad', 'comunidad'])->findOrFail($id);
        
        $tecnicosDisponibles = Tecnico::with('usuario')
                                 ->where('disponible', 1)
                                 ->where('especialidad_id', $incidencia->especialidad_id)
                                 ->get();
        
        return view('admin.servicios.show', compact('incidencia', 'tecnicosDisponibles'));
    }

    public function asignarTecnico(Request $request, $id)
    {
        $request->validate([
            'tecnico_id' => 'required|exists:tecnicos,id' 
        ]);

        $incidencia = Incidencia::findOrFail($id);
        $incidencia->tecnico_id = $request->tecnico_id;
        
        if ($incidencia->estado_id == 1) {
            $incidencia->estado_id = 2;
        }
        
        $incidencia->save();

        return redirect()->route('admin.servicios.index')->with('success', 'Operario asignado con éxito. Estado actualizado a "Asignada".');
    }
}
