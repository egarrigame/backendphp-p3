@extends('layouts.app')

@section('title', 'Crear Aviso - ReparaYa')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Crear Aviso</h2>
        <p class="text-muted">Completa el formulario para crear un nuevo aviso de reparación en nombre de un residente</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ url('/gestora/crear-aviso') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nombre_residente" class="form-label">Nombre del Residente</label>
                        <input type="text" name="nombre_residente" id="nombre_residente" class="form-control" value="{{ old('nombre_residente') }}" required placeholder="Nombre completo del residente">
                    </div>

                    <div class="mb-3">
                        <label for="especialidad_id" class="form-label">Especialidad</label>
                        <select name="especialidad_id" id="especialidad_id" class="form-select" required>
                            <option value="">Selecciona una especialidad</option>
                            @foreach($especialidades as $especialidad)
                                <option value="{{ $especialidad->id }}" {{ old('especialidad_id') == $especialidad->id ? 'selected' : '' }}>
                                    {{ $especialidad->nombre_especialidad }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="zona_id" class="form-label">Zona</label>
                        <select name="zona_id" id="zona_id" class="form-select" required>
                            <option value="">Selecciona una zona</option>
                            @foreach($zonas as $zona)
                                <option value="{{ $zona->id }}" {{ old('zona_id') == $zona->id ? 'selected' : '' }}>
                                    {{ $zona->nombre_zona }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion') }}" required placeholder="Dirección del servicio">
                    </div>

                    <div class="mb-3">
                        <label for="fecha_servicio" class="form-label">Fecha de Servicio</label>
                        <input type="datetime-local" name="fecha_servicio" id="fecha_servicio" class="form-control" value="{{ old('fecha_servicio') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo de Urgencia</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo_urgencia" id="urgencia_estandar" value="estandar" {{ old('tipo_urgencia', 'estandar') === 'estandar' ? 'checked' : '' }}>
                                <label class="form-check-label" for="urgencia_estandar">Estándar</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo_urgencia" id="urgencia_urgente" value="urgente" {{ old('tipo_urgencia') === 'urgente' ? 'checked' : '' }}>
                                <label class="form-check-label" for="urgencia_urgente">Urgente</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" rows="4" required placeholder="Describe el problema o servicio que necesita el residente">{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ url('/gestora/dashboard') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Crear Aviso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
