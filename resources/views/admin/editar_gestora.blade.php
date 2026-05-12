@extends('layouts.app')

@section('title', 'ReparaYa - Editar Gestora')

@section('content')
    <div class="mb-4">
        <h2>Editar Gestora: {{ $gestora->nombre }}</h2>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('producto3/admin/gestoras/actualizar/' . $gestora->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $gestora->nombre) }}" required maxlength="150">
                </div>

                <div class="mb-3">
                    <label for="cif" class="form-label">CIF <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="cif" name="cif" value="{{ old('cif', $gestora->CIF) }}" required maxlength="20">
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion', $gestora->direccion) }}" maxlength="255">
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono', $gestora->telefono) }}" maxlength="20">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $gestora->email) }}" required maxlength="100">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña <small class="text-muted">(dejar vacío para no cambiar)</small></label>
                    <input type="password" class="form-control" id="password" name="password" minlength="6">
                </div>

                <div class="mb-3">
                    <label for="porcentaje_comision" class="form-label">Porcentaje de Comisión (%) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="porcentaje_comision" name="porcentaje_comision" value="{{ old('porcentaje_comision', $gestora->porcentaje_comision) }}" required min="0" max="100" step="0.01">
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="activa" name="activa" {{ old('activa', $gestora->activa) ? 'checked' : '' }}>
                        <label class="form-check-label" for="activa">Gestora Activa</label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <a href="{{ url('producto3/admin/gestoras') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
