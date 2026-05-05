{{-- 
    VISTA: FORMULARIO PARA CREAR UNA NUEVA ESPECIALIDAD
    Extiende el layout principal 'layouts.app'
    Muestra un formulario que envía los datos al método 'store' del controlador
--}}

@extends('layouts.app')

@section('content')
    <h1>Crear Nueva Especialidad</h1>

    {{-- 
        FORMULARIO DE CREACIÓN
        - action: ruta que procesará los datos (método store)
        - method: POST (envío de datos)
        - @csrf: protección contra ataques CSRF (obligatorio en Laravel)
    --}}
    <form action="{{ route('especialidades.store') }}" method="POST">
        @csrf
        
        {{-- Campo: nombre de la especialidad --}}
        <div class="mb-3">
            <label for="nombre_especialidad" class="form-label">Nombre de la especialidad</label>
            
            {{-- 
                Input con:
                - old(): mantiene el valor si hay error en validación
                - @error: muestra mensaje de error si la validación falla
                - is-invalid: clase Bootstrap para resaltar campo con error
            --}}
            <input type="text" class="form-control @error('nombre_especialidad') is-invalid @enderror" 
                   id="nombre_especialidad" name="nombre_especialidad" 
                   value="{{ old('nombre_especialidad') }}" required>
            
            {{-- Mensaje de error de validación --}}
            @error('nombre_especialidad')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Botones de acción --}}
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('especialidades.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection