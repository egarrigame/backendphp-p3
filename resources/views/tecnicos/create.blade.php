{{-- 
    ============================================================
    VISTA: FORMULARIO PARA CREAR UN NUEVO TÉCNICO (create.blade.php)
    ============================================================
    Extiende el layout principal 'layouts.app'
    Muestra un formulario que envía los datos al método 'store' del controlador
--}}

@extends('layouts.app')

@section('content')
    <h1>Crear Nuevo Técnico</h1>

    {{-- 
        FORMULARIO DE CREACIÓN
        - action: ruta que procesará los datos (método store)
        - method: POST (envío de datos)
        - @csrf: protección contra ataques CSRF (obligatorio en Laravel)
    --}}
    <form action="{{ route('tecnicos.store') }}" method="POST">
        @csrf

        {{-- Campo: nombre completo del técnico --}}
        <div class="mb-3">
            <label for="nombre_completo" class="form-label">Nombre Completo</label>
            {{-- 
                old(): mantiene el valor si hay error en validación
                @error: muestra mensaje de error si la validación falla
                is-invalid: clase Bootstrap para resaltar campo con error
            --}}
            <input type="text" class="form-control @error('nombre_completo') is-invalid @enderror"
                   id="nombre_completo" name="nombre_completo" value="{{ old('nombre_completo') }}" required>
            @error('nombre_completo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo: selector de especialidad (relación con tabla especialidades) --}}
        <div class="mb-3">
            <label for="especialidad_id" class="form-label">Especialidad</label>
            <select class="form-control @error('especialidad_id') is-invalid @enderror"
                    id="especialidad_id" name="especialidad_id" required>
                <option value="">Seleccione una especialidad</option>
                {{-- Bucle para mostrar todas las especialidades disponibles --}}
                @foreach($especialidades as $especialidad)
                    <option value="{{ $especialidad->id }}" {{ old('especialidad_id') == $especialidad->id ? 'selected' : '' }}>
                        {{ $especialidad->nombre_especialidad }}
                    </option>
                @endforeach
            </select>
            @error('especialidad_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo: checkbox para disponibilidad --}}
        <div class="mb-3 form-check">
            {{-- 
                value="1": si se marca, envía valor 1
                checked: mantiene marcado si había error en validación
            --}}
            <input type="checkbox" class="form-check-input" id="disponible" name="disponible" value="1" {{ old('disponible') ? 'checked' : '' }}>
            <label class="form-check-label" for="disponible">Disponible</label>
        </div>

        {{-- Botones de acción --}}
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('tecnicos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection