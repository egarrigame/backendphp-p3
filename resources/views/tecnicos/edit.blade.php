{{-- 
    ============================================================
    VISTA: FORMULARIO PARA EDITAR UN TÉCNICO EXISTENTE (edit.blade.php)
    ============================================================
    Extiende el layout principal 'layouts.app'
    Muestra un formulario precargado con los datos del técnico a editar
    Envía los datos al método 'update' del controlador
--}}

@extends('layouts.app')

@section('content')
    <h1>Editar Técnico</h1>

    {{-- 
        FORMULARIO DE EDICIÓN
        - action: ruta con el ID del técnico (ej: /tecnicos/1)
        - method: POST (Laravel usa @method('PUT') para simular PUT/PATCH)
        - @csrf: protección CSRF
        - @method('PUT'): indica que es una actualización
    --}}
    <form action="{{ route('tecnicos.update', $tecnico) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Campo: nombre completo (precargado con el valor actual) --}}
        <div class="mb-3">
            <label for="nombre_completo" class="form-label">Nombre Completo</label>
            {{-- 
                old('campo', $tecnico->campo): 
                - Si hay error, muestra el valor enviado (old)
                - Si no, muestra el valor actual de la base de datos
            --}}
            <input type="text" class="form-control @error('nombre_completo') is-invalid @enderror"
                   id="nombre_completo" name="nombre_completo" 
                   value="{{ old('nombre_completo', $tecnico->nombre_completo) }}" required>
            @error('nombre_completo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo: selector de especialidad (precargado con el valor actual) --}}
        <div class="mb-3">
            <label for="especialidad_id" class="form-label">Especialidad</label>
            <select class="form-control @error('especialidad_id') is-invalid @enderror"
                    id="especialidad_id" name="especialidad_id" required>
                <option value="">Seleccione una especialidad</option>
                {{-- Bucle que marca como 'selected' la especialidad actual del técnico --}}
                @foreach($especialidades as $especialidad)
                    <option value="{{ $especialidad->id }}" 
                        {{ old('especialidad_id', $tecnico->especialidad_id) == $especialidad->id ? 'selected' : '' }}>
                        {{ $especialidad->nombre_especialidad }}
                    </option>
                @endforeach
            </select>
            @error('especialidad_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo: checkbox de disponibilidad (precargado con el valor actual) --}}
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="disponible" name="disponible" value="1" 
                   {{ old('disponible', $tecnico->disponible) ? 'checked' : '' }}>
            <label class="form-check-label" for="disponible">Disponible</label>
        </div>

        {{-- Botones de acción --}}
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('tecnicos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection