@extends('layouts.app')

@section('title', 'ReparaYa - Crear Incidencia')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">Crear Nueva Incidencia</h2>

            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <form method="POST" action="{{ url('producto3/admin/crear-incidencia') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="cliente_id" class="form-label">Cliente</label>
                            <select class="form-select" id="cliente_id" name="cliente_id" required>
                                <option value="">Seleccionar cliente...</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nombre }} ({{ $cliente->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="especialidad_id" class="form-label">Especialidad</label>
                            <select class="form-select" id="especialidad_id" name="especialidad_id" required>
                                <option value="">Seleccionar especialidad...</option>
                                @foreach($especialidades as $especialidad)
                                    <option value="{{ $especialidad->id }}" {{ old('especialidad_id') == $especialidad->id ? 'selected' : '' }}>
                                        {{ $especialidad->nombre_especialidad }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="zona_id" class="form-label">Zona</label>
                            <select class="form-select" id="zona_id" name="zona_id" required>
                                <option value="">Seleccionar zona...</option>
                                @foreach($zonas as $zona)
                                    <option value="{{ $zona->id }}" {{ old('zona_id') == $zona->id ? 'selected' : '' }}>
                                        {{ $zona->nombre_zona }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tipo_urgencia" class="form-label">Urgencia</label>
                            <select class="form-select" id="tipo_urgencia" name="tipo_urgencia" required>
                                <option value="estandar" {{ old('tipo_urgencia') === 'estandar' ? 'selected' : '' }}>Estándar</option>
                                <option value="urgente" {{ old('tipo_urgencia') === 'urgente' ? 'selected' : '' }}>Urgente</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_servicio" class="form-label">Fecha de Servicio</label>
                            <input type="datetime-local" class="form-control" id="fecha_servicio" name="fecha_servicio" value="{{ old('fecha_servicio') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required>{{ old('descripcion') }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Crear Incidencia</button>
                            <a href="{{ url('producto3/admin/incidencias') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
