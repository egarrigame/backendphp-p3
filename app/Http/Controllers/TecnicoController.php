<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use App\Models\Tecnico;
use App\Models\User;
use Illuminate\Http\Request;

class TecnicoController extends Controller
{
    /**
     * List all tecnicos with create form data.
     */
    public function index()
    {
        $tecnicos = Tecnico::with(['usuario', 'especialidad'])->get();
        $especialidades = Especialidad::all();
        $usuarios = User::where('rol', 'tecnico')->get();

        return view('admin.tecnicos', compact('tecnicos', 'especialidades', 'usuarios'));
    }

    /**
     * Store a new tecnico.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required',
            'usuario_id' => 'required|exists:usuarios,id',
            'especialidad_id' => 'required|exists:especialidades,id',
        ]);

        Tecnico::create([
            'nombre_completo' => $request->input('nombre_completo'),
            'usuario_id' => $request->input('usuario_id'),
            'especialidad_id' => $request->input('especialidad_id'),
        ]);

        return redirect()->back()->with('success', 'Técnico creado correctamente.');
    }

    /**
     * Update an existing tecnico.
     */
    public function update(Request $request, $id)
    {
        $tecnico = Tecnico::findOrFail($id);

        $tecnico->update($request->only(['nombre_completo', 'usuario_id', 'especialidad_id', 'disponible']));

        return redirect()->back()->with('success', 'Técnico actualizado correctamente.');
    }

    /**
     * Delete a tecnico.
     */
    public function delete($id)
    {
        $tecnico = Tecnico::findOrFail($id);
        $tecnico->delete();

        return redirect()->back()->with('success', 'Técnico eliminado correctamente.');
    }

    /**
     * Show agenda for the logged-in tecnico.
     */
    public function agenda()
    {
        $tecnico = Tecnico::where('usuario_id', session('user.id'))->first();

        $incidencias = collect();

        if ($tecnico) {
            $incidencias = $tecnico->incidencias()
                ->with(['estado', 'especialidad'])
                ->orderBy('fecha_servicio', 'desc')
                ->get();
        }

        return view('tecnico.agenda', compact('incidencias', 'tecnico'));
    }
}
