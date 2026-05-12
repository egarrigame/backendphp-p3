@extends('layouts.app')

@section('title', 'Gestión de Especialidades - ReparaYa')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Gestión de Especialidades</h2>
        <p class="text-muted">Administra las especialidades del sistema</p>
    </div>
</div>

{{-- Create form --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Nueva Especialidad</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('/producto3/especialidades/guardar') }}">
            @csrf
            <div class="row">
                <div class="col-md-9 mb-3">
                    <label for="nombre_especialidad" class="form-label">Nombre de la Especialidad</label>
                    <input type="text" class="form-control" id="nombre_especialidad" name="nombre_especialidad"
                           value="{{ old('nombre_especialidad') }}" required>
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Crear</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Especialidades table --}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($especialidades as $especialidad)
                        <tr>
                            <td>{{ $especialidad->id }}</td>
                            <td>
                                <form method="POST" action="{{ url('/producto3/especialidades/actualizar/' . $especialidad->id) }}"
                                      class="d-inline-flex align-items-center gap-2">
                                    @csrf
                                    <input type="text" class="form-control form-control-sm" name="nombre_especialidad"
                                           value="{{ $especialidad->nombre_especialidad }}" required>
                                    <button type="submit" class="btn btn-sm btn-warning">Guardar</button>
                                </form>
                            </td>
                            <td>
                                <form method="POST" action="{{ url('/producto3/especialidades/eliminar/' . $especialidad->id) }}"
                                      class="d-inline" onsubmit="return confirm('¿Eliminar esta especialidad?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No hay especialidades registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
