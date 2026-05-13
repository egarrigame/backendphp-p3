@extends('layouts.auth')

@section('title', 'ReparaYa - Iniciar Sesión')

@section('content')
    <div class="text-center mb-4">
        <h1 class="auth-logo">🛠️ ReparaYa</h1>
    </div>

    <h3 class="text-center mb-4">Iniciar Sesión</h3>

    <form method="POST" action="{{ url('/login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </div>
    </form>

    <div class="text-center mt-3">
        <a href="{{ url('/register') }}">¿No tienes cuenta? Regístrate</a>
    </div>
@endsection
