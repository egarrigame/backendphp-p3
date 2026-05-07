<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\User;
use App\Models\Tecnico;
use App\Models\Especialidad;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IncidenciaController extends Controller
{
    /**
     * Muestra el listado de incidencias
     */
    public function index()
    {
        $incidencias = Incidencia::with(['cliente', 'tecnico', 'especialidad'])->get();
        return view('incidencias.index', compact('incidencias'));
    }

    /**
     * Muestra el formulario para crear una nueva incidencia
     */
    public function create()
    {
        $clientes = User::where('rol', 'particular')->get();
        $tecnicos = Tecnico::with('especialidad')->get();
        $especialidades = Especialidad::all();
        return view('incidencias.create', compact('clientes', 'tecnicos', 'especialidades'));
    }

    /**
     * Guarda una nueva incidencia en la base de datos
     */
    public function store(Request $request)
    {
        // Validación básica
        $request->validate([
            'cliente_id' => 'required|exists:usuarios,id',
            'especialidad_id' => 'required|exists:especialidades,id',
            'descripcion' => 'required|string',
            'direccion' => 'required|string|max:255',
            'fecha_servicio' => 'required|date',
            'tipo_urgencia' => 'required|in:Estándar,Urgente',
        ]);

        // Regla de negocio: para servicios Estándar, la fecha no puede ser en el pasado
        $fechaServicio = Carbon::parse($request->fecha_servicio);
        if ($request->tipo_urgencia == 'Estándar' && $fechaServicio->isPast()) {
            return back()->withErrors(['fecha_servicio' => 'Los servicios estándar no pueden tener fecha anterior a hoy.']);
        }

        // Generar localizador único
        $localizador = 'INC' . strtoupper(uniqid());

        Incidencia::create([
            'localizador' => $localizador,
            'cliente_id' => $request->cliente_id,
            'tecnico_id' => $request->tecnico_id,
            'especialidad_id' => $request->especialidad_id,
            'descripcion' => $request->descripcion,
            'direccion' => $request->direccion,
            'fecha_servicio' => $request->fecha_servicio,
            'tipo_urgencia' => $request->tipo_urgencia,
            'estado' => 'Pendiente',
        ]);

        return redirect()->route('incidencias.index')->with('success', 'Incidencia creada correctamente.');
    }

    /**
     * Muestra el formulario para editar una incidencia
     */
    public function edit(Incidencia $incidencia)
    {
        $clientes = User::where('rol', 'particular')->get();
        $tecnicos = Tecnico::with('especialidad')->get();
        $especialidades = Especialidad::all();
        return view('incidencias.edit', compact('incidencia', 'clientes', 'tecnicos', 'especialidades'));
    }

    /**
     * Actualiza una incidencia existente
     */
    public function update(Request $request, Incidencia $incidencia)
    {
        $request->validate([
            'cliente_id' => 'required|exists:usuarios,id',
            'tecnico_id' => 'nullable|exists:tecnicos,id',
            'especialidad_id' => 'required|exists:especialidades,id',
            'descripcion' => 'required|string',
            'direccion' => 'required|string|max:255',
            'fecha_servicio' => 'required|date',
            'tipo_urgencia' => 'required|in:Estándar,Urgente',
            'estado' => 'required|in:Pendiente,Asignada,Finalizada,Cancelada',
        ]);

        $incidencia->update($request->all());

        return redirect()->route('incidencias.index')->with('success', 'Incidencia actualizada correctamente.');
    }

    /**
     * Elimina una incidencia
     */
    public function destroy(Incidencia $incidencia)
    {
        $incidencia->delete();
        return redirect()->route('incidencias.index')->with('success', 'Incidencia eliminada correctamente.');
    }
}