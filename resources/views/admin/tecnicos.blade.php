@extends('layouts.app')

@section('title', 'Gestión de Técnicos - ReparaYa')

@section('content')
<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">🛠️ Gestión de técnicos</h2>
    </div>

    <!-- ALERTAS -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- CREAR TÉCNICO -->
    <div class="card mb-4 p-4">
        <h5 class="mb-3">➕ Nuevo técnico</h5>

        <form method="POST" action="{{ url('/producto3/tecnicos/guardar') }}">
            @csrf
            <div class="row g-2">

                <div class="col-md-4">
                    <input type="text" name="nombre_completo" class="form-control"
                           placeholder="Nombre completo" value="{{ old('nombre_completo') }}" required>
                </div>

                <div class="col-md-4">
                    <select name="especialidad_id" class="form-select" required>
                        <option value="">Especialidad</option>
                        @foreach($especialidades as $especialidad)
                            <option value="{{ $especialidad->id }}" {{ old('especialidad_id') == $especialidad->id ? 'selected' : '' }}>
                                {{ $especialidad->nombre_especialidad }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        Crear
                    </button>
                </div>

            </div>
        </form>
    </div>

    <!-- LISTADO -->
    @if($tecnicos->isEmpty())

        <div class="alert alert-info">
            No hay técnicos registrados.
        </div>

    @else

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Especialidad</th>
                        <th>Estado</th>
                        <th style="min-width: 260px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($tecnicos as $tecnico)

                    <tr>

                        <td>
                            <strong>{{ $tecnico->nombre_completo }}</strong>
                        </td>

                        <td>{{ $tecnico->especialidad->nombre_especialidad ?? 'N/A' }}</td>

                        <!-- DISPONIBLE -->
                        <td>
                            <span class="badge {{ $tecnico->disponible ? 'bg-success' : 'bg-secondary' }}">
                                {{ $tecnico->disponible ? 'Disponible' : 'No disponible' }}
                            </span>
                        </td>

                        <td>

                            <!-- EDITAR -->
                            <form method="POST" action="{{ url('/producto3/tecnicos/actualizar/' . $tecnico->id) }}"
                                  class="d-flex gap-1 mb-2">
                                @csrf

                                <select name="especialidad_id" class="form-select form-select-sm">
                                    @foreach($especialidades as $especialidad)
                                        <option value="{{ $especialidad->id }}"
                                            {{ $especialidad->id == $tecnico->especialidad_id ? 'selected' : '' }}>
                                            {{ $especialidad->nombre_especialidad }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="disponible" class="form-select form-select-sm">
                                    <option value="1" {{ $tecnico->disponible ? 'selected' : '' }}>
                                        Disponible
                                    </option>
                                    <option value="0" {{ !$tecnico->disponible ? 'selected' : '' }}>
                                        No disponible
                                    </option>
                                </select>

                                <button class="btn btn-warning btn-sm">
                                    ✔
                                </button>

                            </form>

                            <!-- ELIMINAR -->
                            <form method="POST" action="{{ url('/producto3/tecnicos/eliminar/' . $tecnico->id) }}"
                                  onsubmit="return confirm('¿Eliminar técnico?');">
                                @csrf

                                <button class="btn btn-danger btn-sm w-100">
                                    ✖ Eliminar
                                </button>

                            </form>

                        </td>

                    </tr>

                @endforeach

                </tbody>
            </table>
        </div>

    @endif

</div>
@endsection
