<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    /**
     * List all especialidades.
     */
    public function index()
    {
        $especialidades = Especialidad::all();

        return view('admin.especialidades', compact('especialidades'));
    }

    /**
     * Store a new especialidad.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_especialidad' => 'required',
        ]);

        Especialidad::create([
            'nombre_especialidad' => $request->input('nombre_especialidad'),
        ]);

        return redirect()->back()->with('success', 'Especialidad creada correctamente.');
    }

    /**
     * Update an existing especialidad.
     */
    public function update(Request $request, $id)
    {
        $especialidad = Especialidad::findOrFail($id);

        $especialidad->update([
            'nombre_especialidad' => $request->input('nombre_especialidad'),
        ]);

        return redirect()->back()->with('success', 'Especialidad actualizada correctamente.');
    }

    /**
     * Delete an especialidad.
     */
    public function delete($id)
    {
        $especialidad = Especialidad::findOrFail($id);
        $especialidad->delete();

        return redirect()->back()->with('success', 'Especialidad eliminada correctamente.');
    }
}
