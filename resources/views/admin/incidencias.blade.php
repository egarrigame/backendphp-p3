@extends('layouts.app')

@section('title', 'ReparaYa - Incidencias')

@section('content')
<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📋 Gestión de incidencias</h2>
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

    <!-- TABLA -->
    <div class="table-responsive">
        <table class="table table-hover align-middle">

            <thead>
                <tr>
                    <th>Localizador</th>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Estado</th>
                    <th>Técnico</th>
                    <th>Urgencia</th>
                    <th>Fecha</th>
                    <th style="min-width: 220px;">Acciones</th>
                </tr>
            </thead>

            <tbody>

            @forelse($incidencias as $incidencia)

                @php
                    $urgente = $incidencia->tipo_urgencia === 'urgente';
                    $urgenciaTexto = $urgente ? 'Urgente' : 'Estándar';

                    // color fila
                    $filaClase = $urgente ? 'fila-urgente' : 'fila-estandar';

                    $estado = $incidencia->estado->nombre_estado ?? '';

                    $estadoClase = match($estado) {
                        'Pendiente' => 'bg-warning text-dark',
                        'Asignada' => 'bg-primary',
                        'Finalizada' => 'bg-success',
                        'Cancelada' => 'bg-danger',
                        default => 'bg-secondary',
                    };
                @endphp

                <tr class="{{ $filaClase }}">

                    <td><strong>{{ $incidencia->localizador }}</strong></td>
                    <td>{{ $incidencia->cliente->nombre ?? 'N/A' }}</td>
                    <td>{{ $incidencia->especialidad->nombre_especialidad ?? 'N/A' }}</td>

                    <!-- ESTADO -->
                    <td>
                        <span class="badge {{ $estadoClase }}">
                            {{ $estado }}
                        </span>
                    </td>

                    <td>{{ $incidencia->tecnico->nombre_completo ?? 'Sin asignar' }}</td>

                    <!-- URGENCIA -->
                    <td>
                        <span class="badge {{ $urgente ? 'bg-danger' : 'bg-success' }}">
                            {{ $urgenciaTexto }}
                        </span>
                    </td>

                    <!-- FECHA -->
                    <td>
                        {{ \Carbon\Carbon::parse($incidencia->fecha_servicio)->format('d/m/Y') }}<br>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($incidencia->fecha_servicio)->format('H:i') }}
                        </small>
                    </td>

                    <td>

                        <!-- ASIGNAR -->
                        @if(in_array($estado, ['Pendiente', 'Asignada']))
                            <form method="POST" action="{{ url('/producto3/admin/asignar-tecnico') }}" class="mb-2 d-flex gap-1">
                                @csrf
                                <input type="hidden" name="incidencia_id" value="{{ $incidencia->id }}">
                                <select name="tecnico_id" class="form-select form-select-sm">
                                    @foreach($tecnicos as $tecnico)
                                        <option value="{{ $tecnico->id }}" {{ $incidencia->tecnico_id == $tecnico->id ? 'selected' : '' }}>
                                            {{ $tecnico->nombre_completo }}
                                        </option>
                                    @endforeach
                                </select>

                                <button class="btn btn-primary btn-sm">
                                    ✔
                                </button>
                            </form>
                        @endif

                        <!-- BOTONES -->
                        <div class="d-flex gap-1">

                            <a href="{{ url('/producto3/admin/editar-incidencia/' . $incidencia->id) }}"
                               class="btn btn-warning btn-sm">
                                ✏️
                            </a>

                            @if($estado !== 'Cancelada')
                                <form method="POST" action="{{ url('/producto3/admin/cancelar-incidencia') }}">
                                    @csrf
                                    <input type="hidden" name="incidencia_id" value="{{ $incidencia->id }}">
                                    <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Cancelar incidencia?')">
                                        ✖
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small">Cancelada</span>
                            @endif

                        </div>

                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No hay incidencias registradas.</td>
                </tr>
            @endforelse

            </tbody>

        </table>
    </div>

</div>
@endsection
