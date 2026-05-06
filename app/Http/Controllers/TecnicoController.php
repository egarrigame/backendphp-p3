<?php

namespace App\Http\Controllers;

use App\Models\Tecnico;
use App\Models\Especialidad;
use Illuminate\Http\Request;

class TecnicoController extends Controller
{
    /**
     * Muestra el listado de todos los técnicos
     * GET /tecnicos
     */
    public function index()
    {
        // Cargamos los técnicos junto con su especialidad (evita consultas N+1)
        $tecnicos = Tecnico::with('especialidad')->get();
        return view('tecnicos.index', compact('tecnicos'));
    }

    /**
     * Muestra el formulario para crear un nuevo técnico
     * GET /tecnicos/create
     */
    public function create()
    {
        // Obtenemos todas las especialidades para el selector del formulario
        $especialidades = Especialidad::all();
        return view('tecnicos.create', compact('especialidades'));
    }

    /**
     * Guarda un nuevo técnico en la base de datos
     * POST /tecnicos
     */
    public function store(Request $request)
    {
        // Validamos los datos del formulario
        $request->validate([
            'nombre_completo' => 'required|string|max:100',
            'especialidad_id' => 'required|exists:especialidades,id',
            'disponible' => 'boolean'
        ]);

        // Crear el nuevo técnico
        Tecnico::create($request->all());

        return redirect()->route('tecnicos.index')
            ->with('success', 'Técnico creado correctamente.');
    }

    /**
     * Muestra el formulario para editar un técnico existente
     * GET /tecnicos/{id}/edit
     */
    public function edit(Tecnico $tecnico)
    {
        // Obtenemos todas las especialidades para el selector
        $especialidades = Especialidad::all();
        return view('tecnicos.edit', compact('tecnico', 'especialidades'));
    }

    /**
     * Actualiza un técnico existente
     * PUT/PATCH /tecnicos/{id}
     */
    public function update(Request $request, Tecnico $tecnico)
    {
        // Validamos los datos
        $request->validate([
            'nombre_completo' => 'required|string|max:100',
            'especialidad_id' => 'required|exists:especialidades,id',
            'disponible' => 'boolean'
        ]);

        // Actualizamos el técnico
        $tecnico->update($request->all());

        return redirect()->route('tecnicos.index')
            ->with('success', 'Técnico actualizado correctamente.');
    }

    /**
     * Elimina un técnico
     * DELETE /tecnicos/{id}
     */
    public function destroy(Tecnico $tecnico)
    {
        $tecnico->delete();

        return redirect()->route('tecnicos.index')
            ->with('success', 'Técnico eliminado correctamente.');
    }
}