<?php

namespace App\Http\Controllers;

// Importamos el modelo Especialidad para interactuar con la base de datos
use App\Models\Especialidad;
// Importamos Request para acceder a los datos del formulario
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    /**
     * Muestra el listado de todas las especialidades.
     */
    public function index()
    {
        // Consulta todos los registros de la tabla 'especialidades'
        $especialidades = Especialidad::all();
        
        // Retorna la vista 'index' pasando los datos
        return view('especialidades.index', compact('especialidades'));
    }

    /**
     * Muestra el formulario para crear una nueva especialidad.
     */
    public function create()
    {
        // Solo retorna la vista con el formulario
        return view('especialidades.create');
    }

    /**
     * Guarda la nueva especialidad en la base de datos.
     */
    public function store(Request $request)
    {
        // Validamos que el campo 'nombre_especialidad' sea obligatorio, texto y máximo 50 caracteres
        $request->validate([
            'nombre_especialidad' => 'required|string|max:50'
        ]);

        // Guardamos el registro usando Eloquent ORM
        Especialidad::create($request->all());
        
        // Redirigimos al listado con un mensaje de éxito
        return redirect()->route('especialidades.index')->with('success', 'Especialidad creada correctamente.');
    }

    /**
     * Muestra los detalles de una especialidad (no se usa en este CRUD).
     */
    public function show($id)
    {
        // Método no implementado porque no es necesario para este CRUD
    }

    /**
     * Muestra el formulario para editar una especialidad existente.
     */
    public function edit(Especialidad $especialidad)
    {
        // Pasamos la especialidad a la vista para precargar los datos
        return view('especialidades.edit', compact('especialidad'));
    }

    /**
     * Actualiza una especialidad existente.
     */
    public function update(Request $request, Especialidad $especialidad)
    {
        // Validamos los datos igual que en el store
        $request->validate([
            'nombre_especialidad' => 'required|string|max:50'
        ]);

        // Actualizamos el registro con los nuevos datos
        $especialidad->update($request->all());
        
        // Redirigimos al listado con mensaje de éxito
        return redirect()->route('especialidades.index')->with('success', 'Especialidad actualizada correctamente.');
    }

    /**
     * Elimina una especialidad.
     */
    public function destroy(Especialidad $especialidad)
    {
        // Eliminamos el registro de la base de datos
        $especialidad->delete();
        
        // Redirigimos al listado con mensaje de éxito
        return redirect()->route('especialidades.index')->with('success', 'Especialidad eliminada correctamente.');
    }
}