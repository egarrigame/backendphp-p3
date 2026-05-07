{{-- 
    ============================================================
    VISTA: FORMULARIO PARA CREAR UN NUEVO USUARIO (create.blade.php)
    ============================================================
    Extiende el layout principal 'layouts.app'
    Muestra un formulario que envía los datos al método 'store' del controlador
--}}

@extends('layouts.app')

@section('content')
    <h1>Crear Nuevo Usuario</h1>

    {{-- 
        FORMULARIO DE CREACIÓN
        - action: ruta que procesará los datos (método store)
        - method: POST (envío de datos)
        - @csrf: protección contra ataques CSRF (obligatorio en Laravel)
    --}}
    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        {{-- Campo: nombre completo --}}
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                   id="nombre" name="nombre" value="{{ old('nombre') }}" required>
            @error('nombre') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>

        {{-- Campo: email (debe ser único) --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                   id="email" name="email" value="{{ old('email') }}" required>
            @error('email') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>

        {{-- Campo: contraseña (mínimo 6 caracteres) --}}
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                   id="password" name="password" required>
            @error('password') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>

        {{-- Campo: confirmación de contraseña --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>

        {{-- Campo: selector de rol --}}
        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select class="form-control @error('rol') is-invalid @enderror" id="rol" name="rol" required>
                <option value="particular" {{ old('rol') == 'particular' ? 'selected' : '' }}>Particular</option>
                <option value="tecnico" {{ old('rol') == 'tecnico' ? 'selected' : '' }}>Técnico</option>
                <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Administrador</option>
            </select>
            @error('rol') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>

        {{-- Campo: teléfono (opcional) --}}
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control @error('telefono') is-invalid @enderror" 
                   id="telefono" name="telefono" value="{{ old('telefono') }}">
            @error('telefono') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>

        {{-- Botones de acción --}}
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection