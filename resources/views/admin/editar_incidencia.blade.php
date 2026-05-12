@extends('layouts.app')

@section('title', 'ReparaYa - Editar Incidencia')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">Editar Incidencia <span class="text-muted">{{ $incidencia->localizador }}</span></h2>

            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <form method="POST" action="{{ url('producto3/admin/actualizar-incidencia/' . $incidencia->id) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="especialidad_id" class="form-label">Especialidad</label>
                            <select class="form-select" id="especialidad_id" name="especialidad_id" required>
                                @foreach($especialidades as $especialidad)
                                    <option value="{{ $especialidad->id }}" {{ (old('especialidad_id', $incidencia->especialidad_id) == $especialidad->id) ? 'selected' : '' }}>
                                        {{ $especialidad->nombre_especialidad }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="estado_id" class="form-label">Estado</label>
                            <select class="form-select" id="estado_id" name="estado_id" required>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->id }}" {{ (old('estado_id', $incidencia->estado_id) == $estado->id) ? 'selected' : '' }}>
                                        {{ $estado->nombre_estado }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tipo_urgencia" class="form-label">Urgencia</label>
                            <select class="form-select" id="tipo_urgencia" name="tipo_urgencia" required>
                                <option value="estandar" {{ old('tipo_urgencia', $incidencia->tipo_urgencia) === 'estandar' ? 'selected' : '' }}>Estándar</option>
                                <option value="urgente" {{ old('tipo_urgencia', $incidencia->tipo_urgencia) === 'urgente' ? 'selected' : '' }}>Urgente</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_servicio" class="form-label">Fecha de Servicio</label>
                            <input type="datetime-local" class="form-control" id="fecha_servicio" name="fecha_servicio"
                                value="{{ old('fecha_servicio', $incidencia->fecha_servicio ? \Carbon\Carbon::parse($incidencia->fecha_servicio)->format('Y-m-d\TH:i') : '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion"
                                value="{{ old('direccion', $incidencia->direccion) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required>{{ old('descripcion', $incidencia->descripcion) }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <a href="{{ url('producto3/admin/incidencias') }}" class="btn btn-secondary">Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
