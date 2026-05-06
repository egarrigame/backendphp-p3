{{-- 
    ============================================================
    VISTA: LISTADO DE TÉCNICOS (index.blade.php)
    ============================================================
    Extiende el layout principal 'layouts.app'
    Muestra una tabla con todos los técnicos y su especialidad
    Incluye botones para: Crear, Editar, Eliminar
--}}

@extends('layouts.app')

@section('content')
    {{-- Título de la página --}}
    <h1>Gestión de Técnicos</h1>

    {{-- Botón para crear un nuevo técnico --}}
    {{-- route('tecnicos.create') genera la URL /tecnicos/create --}}
    <a href="{{ route('tecnicos.create') }}" class="btn btn-primary mb-3">+ Nuevo Técnico</a>

    {{-- Mensaje flash de éxito (aparece después de crear, editar o eliminar) --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- TABLA DE TÉCNICOS --}}
    <table class="table table-bordered table-striped">
        {{-- CABECERA DE LA TABLA --}}
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Especialidad</th>
                <th>Disponible</th>
                <th>Acciones</th>
            </tr>
        </thead>

        {{-- CUERPO DE LA TABLA (datos dinámicos) --}}
        <tbody>
            {{-- Bucle foreach: recorre cada técnico recibido desde el controlador --}}
            @foreach($tecnicos as $tecnico)
            <tr>
                {{-- ID del técnico --}}
                <td>{{ $tecnico->id }} </td>
                
                {{-- Nombre completo del técnico --}}
                <td>{{ $tecnico->nombre_completo }} </td>
                
                {{-- 
                    Nombre de la especialidad (relación)
                    El operador ?? muestra 'Sin asignar' si la relación es null
                --}}
                <td>{{ $tecnico->especialidad->nombre_especialidad ?? 'Sin asignar' }} </td>
                
                {{-- 
                    Disponibilidad: muestra ✅ Sí si es 1 (true), ❌ No si es 0 (false)
                    El operador ternario ? : es una forma compacta de if/else
                --}}
                <td>{{ $tecnico->disponible ? '✅ Sí' : '❌ No' }} </td>
                
                {{-- Columna de acciones (botones) --}}
                <td>
                    {{-- BOTÓN EDITAR --}}
                    <a href="{{ route('tecnicos.edit', $tecnico) }}" class="btn btn-warning btn-sm">Editar</a>

                    {{-- BOTÓN ELIMINAR con confirmación --}}
                    <form action="{{ route('tecnicos.destroy', $tecnico) }}" method="POST" style="display:inline">
                        @csrf                          {{-- Protección CSRF de Laravel --}}
                        @method('DELETE')              {{-- Simula método HTTP DELETE --}}
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este técnico?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection