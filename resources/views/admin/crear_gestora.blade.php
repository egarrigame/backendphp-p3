@extends('layouts.app')

@section('title', 'ReparaYa - Crear Gestora')

@section('content')
    <div class="mb-4">
        <h2>Crear Nueva Gestora</h2>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/gestoras/crear') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required maxlength="150">
                </div>

                <div class="mb-3">
                    <label for="cif" class="form-label">CIF <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="cif" name="cif" value="{{ old('cif') }}" required maxlength="20">
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}" maxlength="255">
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}" maxlength="20">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required maxlength="100">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password" name="password" required minlength="6">
                </div>

                <div class="mb-3">
                    <label for="porcentaje_comision" class="form-label">Porcentaje de Comisión (%) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="porcentaje_comision" name="porcentaje_comision" value="{{ old('porcentaje_comision') }}" required min="0" max="100" step="0.01">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Crear Gestora</button>
                    <a href="{{ url('admin/gestoras') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
