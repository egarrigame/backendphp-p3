<?php

namespace App\Http\Controllers;

use App\Models\EmpresaGestora;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form or redirect if already authenticated.
     */
    public function showLogin()
    {
        if (session('user')) {
            return $this->redirectByRole(session('user.rol'));
        }

        if (session('gestora')) {
            return redirect('/gestora/dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login for both usuarios and empresas gestoras.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        // First, search in usuarios table
        $user = User::where('email', $email)->first();

        if ($user) {
            if (Hash::check($password, $user->password)) {
                session([
                    'user' => [
                        'id' => $user->id,
                        'nombre' => $user->nombre,
                        'email' => $user->email,
                        'rol' => $user->rol,
                    ]
                ]);

                return $this->redirectByRole($user->rol);
            }

            return redirect()->back()
                ->withErrors(['email' => 'Credenciales incorrectas'])
                ->withInput();
        }

        // If not found in usuarios, search in empresas_gestoras
        $gestora = EmpresaGestora::where('email', $email)->first();

        if ($gestora) {
            if (!$gestora->activa) {
                return redirect()->back()
                    ->withErrors(['email' => 'Cuenta inactiva'])
                    ->withInput();
            }

            if (Hash::check($password, $gestora->password)) {
                session([
                    'gestora' => [
                        'id' => $gestora->id,
                        'nombre' => $gestora->nombre,
                        'email' => $gestora->email,
                    ]
                ]);

                return redirect('/gestora/dashboard');
            }
        }

        return redirect()->back()
            ->withErrors(['email' => 'Credenciales incorrectas'])
            ->withInput();
    }

    /**
     * Show registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration (particular role only).
     */
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'email' => 'required|unique:usuarios,email',
            'telefono' => 'required',
            'password' => 'required|min:4',
        ]);

        User::create([
            'nombre' => $request->input('nombre'),
            'email' => $request->input('email'),
            'telefono' => $request->input('telefono'),
            'password' => Hash::make($request->input('password')),
            'rol' => 'particular',
        ]);

        return redirect('/login')->with('success', 'Registro exitoso. Inicia sesión.');
    }

    /**
     * Logout: flush session and redirect to login.
     */
    public function logout()
    {
        session()->flush();

        return redirect('/login');
    }

    /**
     * Redirect user based on their role.
     */
    private function redirectByRole(string $rol)
    {
        return match ($rol) {
            'admin' => redirect('/admin/dashboard'),
            'tecnico' => redirect('/tecnico/agenda'),
            'particular' => redirect('/cliente/dashboard'),
            default => redirect('/login'),
        };
    }
}
