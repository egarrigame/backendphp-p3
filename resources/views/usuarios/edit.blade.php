{{-- 
    ============================================================
    VISTA: FORMULARIO PARA EDITAR UN USUARIO EXISTENTE (edit.blade.php)
    ============================================================
    Extiende el layout principal 'layouts.app'
    Muestra un formulario precargado con los datos del usuario a editar
    Envía los datos al método 'update' del controlador
--}}

@extends('layouts.app')

@section('content')
    <h1>Editar Usuario</h1>

    {{-- 
        FORMULARIO DE EDICIÓN
        - action: ruta con el ID del usuario (ej: /usuarios/1)
        - method: POST (Laravel usa @method('PUT') para simular PUT/PATCH)
        - @csrf: protección CSRF
        - @method('PUT'): indica que es una actualización
    --}}
    <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Campo: nombre (precargado con el valor actual) --}}
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                   id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
            @error('nombre') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>

        {{-- Campo: email (precargado, verifica unicidad excepto este usuario) --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                   id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
            @error('email') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>

        {{-- Campo: nueva contraseña (opcional, solo si se quiere cambiar) --}}
        <div class="mb-3">
            <label for="password" class="form-label">Nueva Contraseña (dejar en blanco para no cambiar)</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                   id="password" name="password">
            @error('password') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>

        {{-- Campo: confirmación de nueva contraseña --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        {{-- Campo: selector de rol (precargado con el valor actual) --}}
        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select class="form-control @error('rol') is-invalid @enderror" id="rol" name="rol" required>
                <option value="particular" {{ old('rol', $usuario->rol) == 'particular' ? 'selected' : '' }}>Particular</option>
                <option value="tecnico" {{ old('rol', $usuario->rol) == 'tecnico' ? 'selected' : '' }}>Técnico</option>
                <option value="admin" {{ old('rol', $usuario->rol) == 'admin' ? 'selected' : '' }}>Administrador</option>
            </select>
            @error('rol') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>

        {{-- Campo: teléfono (opcional, precargado) --}}
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control @error('telefono') is-invalid @enderror" 
                   id="telefono" name="telefono" value="{{ old('telefono', $usuario->telefono) }}">
            @error('telefono') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>

        {{-- Botones de acción --}}
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection
