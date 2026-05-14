@extends('layouts.auth')

@section('title', 'ReparaYa - Registro')

@section('logo')
    <div class="text-center mb-4">
        <h1 class="auth-logo">🛠️ ReparaYa</h1>
    </div>
@endsection

@section('content')
    <h3 class="text-center mb-4">Registro</h3>

    <form method="POST" action="{{ url('/register') }}">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Registrarse</button>
        </div>
    </form>

    <div class="text-center mt-3">
        <a href="{{ url('/login') }}">¿Ya tienes cuenta? Inicia sesión</a>
    </div>
@endsection
