{{-- 
    ============================================================
    VISTA: LISTADO DE USUARIOS (index.blade.php)
    ============================================================
    Extiende el layout principal 'layouts.app'
    Muestra una tabla con todos los usuarios registrados
    Incluye botones para: Crear, Editar, Eliminar
--}}

@extends('layouts.app')

@section('content')
    {{-- Título de la página --}}
    <h1>Gestión de Usuarios</h1>

    {{-- Botón para crear un nuevo usuario --}}
    {{-- route('usuarios.create') genera la URL /usuarios/create --}}
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary mb-3">+ Nuevo Usuario</a>

    {{-- Mensaje flash de éxito (aparece después de crear, editar o eliminar) --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- TABLA DE USUARIOS --}}
    <table class="table table-bordered table-striped">
        {{-- CABECERA DE LA TABLA --}}
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>

        {{-- CUERPO DE LA TABLA (datos dinámicos) --}}
        <tbody>
            {{-- Bucle foreach: recorre cada usuario recibido desde el controlador --}}
            @foreach($usuarios as $usuario)
            <tr>
                {{-- ID del usuario --}}
                <td>{{ $usuario->id }}</td>
                
                {{-- Nombre del usuario --}}
                <td>{{ $usuario->nombre }}</td>
                
                {{-- Email del usuario --}}
                <td>{{ $usuario->email }}</td>
                
                {{-- 
                    Rol del usuario con icono según el tipo
                    admin → , tecnico → , particular → 
                --}}
                <td>
                    @if($usuario->rol == 'admin')
                         Admin
                    @elseif($usuario->rol == 'tecnico')
                        🔧 Técnico
                    @else
                         Particular
                    @endif
                </td>
                
                {{-- Teléfono (muestra - si no tiene) --}}
                <td>{{ $usuario->telefono ?? '-' }}</td>
                
                {{-- Columna de acciones (botones) --}}
                <td>
                    {{-- BOTÓN EDITAR --}}
                    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning btn-sm">Editar</a>

                    {{-- BOTÓN ELIMINAR con confirmación --}}
                    <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display:inline">
                        @csrf                          {{-- Protección CSRF de Laravel --}}
                        @method('DELETE')              {{-- Simula método HTTP DELETE --}}
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este usuario?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection