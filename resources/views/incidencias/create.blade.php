@extends('layouts.app')

@section('content')
    <h1>Crear Nueva Incidencia</h1>

    <form action="{{ route('incidencias.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="cliente_id" class="form-label">Cliente</label>
            <select class="form-control @error('cliente_id') is-invalid @enderror" id="cliente_id" name="cliente_id" required>
                <option value="">Seleccione un cliente</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                        {{ $cliente->nombre }} ({{ $cliente->email }})
                    </option>
                @endforeach
            </select>
            @error('cliente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="especialidad_id" class="form-label">Especialidad</label>
            <select class="form-control @error('especialidad_id') is-invalid @enderror" id="especialidad_id" name="especialidad_id" required>
                <option value="">Seleccione una especialidad</option>
                @foreach($especialidades as $especialidad)
                    <option value="{{ $especialidad->id }}" {{ old('especialidad_id') == $especialidad->id ? 'selected' : '' }}>
                        {{ $especialidad->nombre_especialidad }}
                    </option>
                @endforeach
            </select>
            @error('especialidad_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="tecnico_id" class="form-label">Técnico (opcional)</label>
            <select class="form-control" id="tecnico_id" name="tecnico_id">
                <option value="">Sin asignar</option>
                @foreach($tecnicos as $tecnico)
                    <option value="{{ $tecnico->id }}" {{ old('tecnico_id') == $tecnico->id ? 'selected' : '' }}>
                        {{ $tecnico->nombre_completo }} ({{ $tecnico->especialidad->nombre_especialidad ?? 'Sin especialidad' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3" required>{{ old('descripcion') }}</textarea>
            @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion') }}" required>
            @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="fecha_servicio" class="form-label">Fecha del Servicio</label>
            <input type="datetime-local" class="form-control @error('fecha_servicio') is-invalid @enderror" id="fecha_servicio" name="fecha_servicio" value="{{ old('fecha_servicio') }}" required>
            @error('fecha_servicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="tipo_urgencia" class="form-label">Tipo de Urgencia</label>
            <select class="form-control @error('tipo_urgencia') is-invalid @enderror" id="tipo_urgencia" name="tipo_urgencia" required>
                <option value="Estándar" {{ old('tipo_urgencia') == 'Estándar' ? 'selected' : '' }}>Estándar</option>
                <option value="Urgente" {{ old('tipo_urgencia') == 'Urgente' ? 'selected' : '' }}>Urgente</option>
            </select>
            @error('tipo_urgencia') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('incidencias.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection