{{-- 
    VISTA: FORMULARIO PARA CREAR UN NUEVO SERVICIO
    La gestora selecciona comunidad, escribe descripción y precio
    El sistema calcula la comisión automáticamente
--}}

@extends('layouts.app')

@section('content')
    <h1>Crear Nuevo Servicio</h1>
    
    <form action="{{ route('gestora.servicios.store') }}" method="POST">
        @csrf
        
        {{-- Campo: Comunidad (solo las que gestiona esta gestora) --}}
        <div class="mb-3">
            <label for="comunidad_id" class="form-label">Comunidad</label>
            <select class="form-control @error('comunidad_id') is-invalid @enderror" 
                    id="comunidad_id" name="comunidad_id" required>
                <option value="">Seleccione una comunidad</option>
                @foreach($comunidades as $comunidad)
                    <option value="{{ $comunidad->id }}" {{ old('comunidad_id') == $comunidad->id ? 'selected' : '' }}>
                        {{ $comunidad->nombre }} - {{ $comunidad->direccion }}
                    </option>
                @endforeach
            </select>
            @error('comunidad_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        {{-- Campo: Descripción del servicio --}}
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                      id="descripcion" name="descripcion" rows="3" required>{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        {{-- Campo: Precio base --}}
        <div class="mb-3">
            <label for="precio_base" class="form-label">Precio Base (€)</label>
            <input type="number" step="0.01" class="form-control @error('precio_base') is-invalid @enderror" 
                   id="precio_base" name="precio_base" value="{{ old('precio_base') }}" required>
            <small class="form-text text-muted">
                La comisión se calculará automáticamente según tu porcentaje pactado.
            </small>
            @error('precio_base')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        {{-- Botones de acción --}}
        <button type="submit" class="btn btn-primary">Guardar Servicio</button>
        <a href="{{ route('gestora.servicios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection