@extends('layouts.app')

@section('title', 'ReparaYa - Editar Incidencia')

@section('content')
<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">✏️ Editar incidencia</h2>
    </div>

    <!-- ALERTAS -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- FORM -->
    <div class="card p-4">
        <form method="POST" action="{{ url('/producto3/admin/actualizar-incidencia/' . $incidencia->id) }}">
            @csrf

            <div class="row">

                <!-- CLIENTE -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Cliente</label>
                    <input type="text" class="form-control"
                           value="{{ $incidencia->cliente->nombre ?? '' }}" disabled>
                </div>

                <!-- ESTADO -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado_id" class="form-select">
                        @foreach($estados as $estado)
                            <option value="{{ $estado->id }}"
                                {{ (old('estado_id', $incidencia->estado_id) == $estado->id) ? 'selected' : '' }}>
                                {{ $estado->nombre_estado }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- ESPECIALIDAD -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Especialidad</label>
                    <select name="especialidad_id" class="form-select">
                        @foreach($especialidades as $especialidad)
                            <option value="{{ $especialidad->id }}"
                                {{ (old('especialidad_id', $incidencia->especialidad_id) == $especialidad->id) ? 'selected' : '' }}>
                                {{ $especialidad->nombre_especialidad }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- URGENCIA -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipo de servicio</label>
                    <select name="tipo_urgencia" class="form-select">
                        <option value="estandar"
                            {{ old('tipo_urgencia', $incidencia->tipo_urgencia) === 'estandar' ? 'selected' : '' }}>
                            Estándar
                        </option>
                        <option value="urgente"
                            {{ old('tipo_urgencia', $incidencia->tipo_urgencia) === 'urgente' ? 'selected' : '' }}>
                            Urgente
                        </option>
                    </select>
                </div>

                <!-- FECHA -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Fecha del servicio</label>
                    <input type="datetime-local" name="fecha_servicio"
                           class="form-control"
                           value="{{ old('fecha_servicio', $incidencia->fecha_servicio ? \Carbon\Carbon::parse($incidencia->fecha_servicio)->format('Y-m-d\TH:i') : '') }}">
                </div>

                <!-- DIRECCIÓN -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control"
                           value="{{ old('direccion', $incidencia->direccion) }}" required>
                </div>

                <!-- DESCRIPCIÓN -->
                <div class="col-12 mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="4" required>{{ old('descripcion', trim($incidencia->descripcion)) }}</textarea>
                </div>

            </div>

            <!-- BOTONES -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="{{ url('/producto3/admin/incidencias') }}" class="btn btn-secondary">
                    ← Volver
                </a>
                <button class="btn btn-primary">
                    💾 Guardar cambios
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
