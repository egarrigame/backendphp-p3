{{-- 
    ============================================================
    VISTA: LISTADO DE ESPECIALIDADES (index.blade.php)
    ============================================================
    Esta vista extiende el layout principal 'layouts.app'
    Muestra una tabla con todas las especialidades
    Incluye botones para: Crear, Editar, Eliminar
--}}

@extends('layouts.app')

@section('content')
    {{-- Título de la página --}}
    <h1>Gestión de Especialidades</h1>

    {{-- Botón para crear una nueva especialidad --}}
    {{-- route('especialidades.create') genera la URL /especialidades/create --}}
    <a href="{{ route('especialidades.create') }}" class="btn btn-primary mb-3">+ Nueva Especialidad</a>

    {{-- Mensaje flash de éxito (aparece después de crear, editar o eliminar) --}}
    {{-- session('success') recupera el mensaje guardado en el controlador --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- TABLA DE ESPECIALIDADES --}}
    {{-- Clases de Bootstrap: table, table-bordered, table-striped --}}
    <table class="table table-bordered table-striped">
        
        {{-- CABECERA DE LA TABLA --}}
        <thead class="table-dark">
            <tr>
                <th>ID</th>                          {{-- Columna: Identificador numérico --}}
                <th>Nombre de la Especialidad</th>   {{-- Columna: Nombre (ej. Fontanería) --}}
                <th>Acciones</th>                    {{-- Columna: Botones Editar/Eliminar --}}
            </tr>
        </thead>

        {{-- CUERPO DE LA TABLA (datos dinámicos) --}}
        <tbody>
            {{-- Bucle foreach: recorre cada especialidad recibida desde el controlador --}}
            {{-- $especialidades es un array/colección de objetos de tipo Especialidad --}}
            @foreach($especialidades as $especialidad)
            <tr>
                {{-- Mostrar el ID de la especialidad --}}
                <td>{{ $especialidad->id }}</td>
                
                {{-- Mostrar el nombre de la especialidad --}}
                <td>{{ $especialidad->nombre_especialidad }}</td>
                
                {{-- Columna de acciones (botones) --}}
                <td>
                    {{-- BOTÓN EDITAR --}}
                    {{-- route('especialidades.edit', $especialidad) genera URL: /especialidades/{id}/edit --}}
                    <a href="{{ route('especialidades.edit', $especialidad) }}" class="btn btn-warning btn-sm">Editar</a>

                    {{-- BOTÓN ELIMINAR con confirmación --}}
                    {{-- Formulario porque DELETE no es soportado por enlaces normales --}}
                    <form action="{{ route('especialidades.destroy', $especialidad) }}" method="POST" style="display:inline">
                        @csrf                          {{-- Protección CSRF de Laravel --}}
                        @method('DELETE')              {{-- Simula método HTTP DELETE --}}
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta especialidad?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection