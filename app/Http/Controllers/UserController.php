<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Show user profile data from session.
     */
    public function perfil()
    {
        $user = User::find(session('user.id'));

        if (!$user) {
            return redirect(url('/login'))->with('error', 'Usuario no encontrado.');
        }

        return view('user.perfil', compact('user'));
    }

    /**
     * Update user profile (nombre, email, telefono, optional password).
     */
    public function updatePerfil(Request $request)
    {
        $user = User::find(session('user.id'));

        if (!$user) {
            return redirect(url('/login'))->with('error', 'Usuario no encontrado.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuarios,email,' . $user->id,
            'telefono' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->nombre = $request->input('nombre');
        $user->email = $request->input('email');
        $user->telefono = $request->input('telefono');

        if ($request->filled('password')) {
            $user->password = password_hash($request->input('password'), PASSWORD_DEFAULT);
        }

        $user->save();

        // Update session data
        session([
            'user.nombre' => $user->nombre,
            'user.email' => $user->email,
        ]);

        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
    }
}
