@extends('layouts.app')

@section('title', 'Gestión de Especialidades - ReparaYa')

@section('content')
<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">🧩 Gestión de especialidades</h2>
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

    <!-- CREAR -->
    <div class="card p-3 mb-4">
        <h5 class="mb-3">➕ Nueva especialidad</h5>

        <form method="POST" action="{{ url('/producto3/especialidades/guardar') }}" class="d-flex gap-2">
            @csrf

            <input type="text"
                   name="nombre_especialidad"
                   class="form-control"
                   placeholder="Ej: Fontanería"
                   value="{{ old('nombre_especialidad') }}"
                   required>

            <button class="btn btn-primary">
                Añadir
            </button>

        </form>
    </div>

    <!-- LISTADO -->
    @if($especialidades->isEmpty())

        <div class="alert alert-info">
            No hay especialidades registradas.
        </div>

    @else

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th style="min-width: 220px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($especialidades as $especialidad)

                    <tr>

                        <!-- ID -->
                        <td>
                            <span class="badge bg-secondary">
                                #{{ $especialidad->id }}
                            </span>
                        </td>

                        <!-- NOMBRE EDITABLE -->
                        <td>
                            <form method="POST"
                                  action="{{ url('/producto3/especialidades/actualizar/' . $especialidad->id) }}"
                                  class="d-flex gap-2">
                                @csrf

                                <input type="text"
                                       name="nombre_especialidad"
                                       class="form-control form-control-sm"
                                       value="{{ $especialidad->nombre_especialidad }}"
                                       required>

                                <button class="btn btn-warning btn-sm">
                                    ✔
                                </button>

                            </form>
                        </td>

                        <!-- ACCIONES -->
                        <td>

                            <form method="POST"
                                  action="{{ url('/producto3/especialidades/eliminar/' . $especialidad->id) }}"
                                  onsubmit="return confirm('¿Eliminar esta especialidad?');">
                                @csrf

                                <button class="btn btn-outline-danger btn-sm">
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
