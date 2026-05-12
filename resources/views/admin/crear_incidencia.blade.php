@extends('layouts.app')

@section('title', 'ReparaYa - Crear Incidencia')

@section('content')
<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">➕ Crear nueva incidencia</h2>
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

        <form method="POST" action="{{ url('/producto3/admin/crear-incidencia') }}">
            @csrf

            <div class="row">

                <!-- CLIENTE -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Cliente</label>
                    <select name="cliente_id" class="form-select" required>
                        <option value="">Seleccionar cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nombre }} ({{ $cliente->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- ESPECIALIDAD -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Servicio</label>
                    <select name="especialidad_id" class="form-select" required>
                        @foreach($especialidades as $especialidad)
                            <option value="{{ $especialidad->id }}" {{ old('especialidad_id') == $especialidad->id ? 'selected' : '' }}>
                                {{ $especialidad->nombre_especialidad }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- FECHA -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Fecha del servicio</label>
                    <input type="datetime-local"
                           name="fecha_servicio"
                           class="form-control"
                           value="{{ old('fecha_servicio') }}"
                           required>
                </div>

                <!-- URGENCIA -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipo de servicio</label>
                    <select name="tipo_urgencia" class="form-select" required>
                        <option value="estandar" {{ old('tipo_urgencia') === 'estandar' ? 'selected' : '' }}>Estándar</option>
                        <option value="urgente" {{ old('tipo_urgencia') === 'urgente' ? 'selected' : '' }}>Urgente</option>
                    </select>
                </div>

                <!-- DIRECCIÓN -->
                <div class="col-12 mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text"
                           name="direccion"
                           class="form-control"
                           placeholder="Calle, número, ciudad..."
                           value="{{ old('direccion') }}"
                           required>
                </div>

                <!-- DESCRIPCIÓN -->
                <div class="col-12 mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion"
                              class="form-control"
                              rows="4"
                              placeholder="Describe la avería..."
                              required>{{ old('descripcion') }}</textarea>
                </div>

            </div>

            <!-- BOTONES -->
            <div class="d-flex justify-content-end gap-2 mt-3">

                <a href="{{ url('/producto3/admin/dashboard') }}" class="btn btn-secondary">
                    Volver
                </a>

                <button class="btn btn-primary">
                    Crear incidencia
                </button>

            </div>

        </form>

    </div>

</div>
@endsection
