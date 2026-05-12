@extends('layouts.app')

@section('title', 'Gestión de Técnicos - ReparaYa')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Gestión de Técnicos</h2>
        <p class="text-muted">Administra los técnicos del sistema</p>
    </div>
</div>

{{-- Create form --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Nuevo Técnico</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('/producto3/tecnicos/guardar') }}">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="nombre_completo" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre_completo" name="nombre_completo"
                           value="{{ old('nombre_completo') }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="usuario_id" class="form-label">Usuario</label>
                    <select class="form-select" id="usuario_id" name="usuario_id" required>
                        <option value="">Seleccionar usuario...</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->nombre }} ({{ $usuario->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="especialidad_id" class="form-label">Especialidad</label>
                    <select class="form-select" id="especialidad_id" name="especialidad_id" required>
                        <option value="">Seleccionar especialidad...</option>
                        @foreach($especialidades as $especialidad)
                            <option value="{{ $especialidad->id }}">{{ $especialidad->nombre_especialidad }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Crear</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tecnicos table --}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Usuario</th>
                        <th>Especialidad</th>
                        <th>Disponible</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tecnicos as $tecnico)
                        <tr>
                            <td>{{ $tecnico->id }}</td>
                            <td>{{ $tecnico->nombre_completo }}</td>
                            <td>{{ $tecnico->usuario ? $tecnico->usuario->email : 'N/A' }}</td>
                            <td>{{ $tecnico->especialidad ? $tecnico->especialidad->nombre_especialidad : 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $tecnico->disponible ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $tecnico->disponible ? 'Sí' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $tecnico->id }}">Editar</button>
                                <form method="POST" action="{{ url('/producto3/tecnicos/eliminar/' . $tecnico->id) }}"
                                      class="d-inline" onsubmit="return confirm('¿Eliminar este técnico?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>

                        {{-- Edit Modal --}}
                        <div class="modal fade" id="editModal{{ $tecnico->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ url('/producto3/tecnicos/actualizar/' . $tecnico->id) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Técnico</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nombre Completo</label>
                                                <input type="text" class="form-control" name="nombre_completo"
                                                       value="{{ $tecnico->nombre_completo }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Usuario</label>
                                                <select class="form-select" name="usuario_id" required>
                                                    @foreach($usuarios as $usuario)
                                                        <option value="{{ $usuario->id }}"
                                                            {{ $tecnico->usuario_id == $usuario->id ? 'selected' : '' }}>
                                                            {{ $usuario->nombre }} ({{ $usuario->email }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Especialidad</label>
                                                <select class="form-select" name="especialidad_id" required>
                                                    @foreach($especialidades as $especialidad)
                                                        <option value="{{ $especialidad->id }}"
                                                            {{ $tecnico->especialidad_id == $especialidad->id ? 'selected' : '' }}>
                                                            {{ $especialidad->nombre_especialidad }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Disponible</label>
                                                <select class="form-select" name="disponible">
                                                    <option value="1" {{ $tecnico->disponible ? 'selected' : '' }}>Sí</option>
                                                    <option value="0" {{ !$tecnico->disponible ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay técnicos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
