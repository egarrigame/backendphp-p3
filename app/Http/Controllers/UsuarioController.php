<?php

namespace App\Http\Controllers;

// Importamos el modelo User para interactuar con la tabla 'usuarios'
use App\Models\User;
// Importamos Request para acceder a los datos del formulario
use Illuminate\Http\Request;
// Importamos Hash para encriptar las contraseñas
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Muestra el listado de todos los usuarios
     * GET /usuarios
     */
    public function index()
    {
        // Consulta todos los registros de la tabla 'usuarios'
        $usuarios = User::all();
        
        // Retorna la vista 'index' pasando los datos
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario
     * GET /usuarios/create
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Guarda un nuevo usuario en la base de datos
     * POST /usuarios
     */
    public function store(Request $request)
    {
        // Validamos los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:100',                    // Obligatorio, texto, máx 100
            'email' => 'required|email|unique:usuarios,email',       // Obligatorio, email, único en la tabla
            'password' => 'required|min:6|confirmed',                // Obligatorio, mínimo 6, coincide con confirmación
            'rol' => 'required|in:admin,particular,tecnico',         // Obligatorio, solo estos valores
            'telefono' => 'nullable|string|max:20',                  // Opcional, texto, máx 20
        ]);

        // Creamos el usuario con los datos validados
        // La contraseña se guarda encriptada con Hash::make()
        User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'telefono' => $request->telefono,
        ]);

        // Redirigimos al listado con mensaje de éxito
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Muestra el formulario para editar un usuario existente
     * GET /usuarios/{id}/edit
     */
    public function edit(User $usuario)
    {
        // Pasamos el usuario a la vista para precargar los datos
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Actualiza un usuario existente
     * PUT/PATCH /usuarios/{id}
     */
    public function update(Request $request, User $usuario)
    {
        // Validamos los datos (el email debe ser único excepto el del propio usuario)
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email,' . $usuario->id,  // Ignora el email actual
            'rol' => 'required|in:admin,particular,tecnico',
            'telefono' => 'nullable|string|max:20',
        ]);

        // Preparamos los datos a actualizar
        $data = [
            'nombre' => $request->nombre,
            'email' => $request->email,
            'rol' => $request->rol,
            'telefono' => $request->telefono,
        ];

        // Si se ha proporcionado una nueva contraseña, la validamos y encriptamos
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        // Actualizamos el usuario
        $usuario->update($data);

        // Redirigimos al listado con mensaje de éxito
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Elimina un usuario
     * DELETE /usuarios/{id}
     */
    public function destroy(User $usuario)
    {
        // Eliminamos el registro de la base de datos
        $usuario->delete();
        
        // Redirigimos al listado con mensaje de éxito
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}