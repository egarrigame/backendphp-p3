@extends('layouts.app')

@section('title', 'Mis Avisos - ReparaYa')

@section('content')
<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📋 Mis avisos</h2>

        <a href="{{ url('/cliente/nueva-incidencia') }}" class="btn btn-primary">
            ➕ Nueva incidencia
        </a>
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

    @if($incidencias->isEmpty())
        <div class="alert alert-info">
            No tienes incidencias registradas.
        </div>
    @else

        <!-- TABLA PRO -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>Localizador</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Dirección</th>
                        <th>Urgencia</th>
                        <th style="min-width: 140px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($incidencias as $incidencia)

                    @php
                        $urgente = strtolower($incidencia->tipo_urgencia) === 'urgente';
                        $urgenciaTexto = $urgente ? 'Urgente' : 'Estándar';

                        // COLOR FILA
                        $filaClase = $urgente ? 'fila-urgente' : 'fila-estandar';

                        // REGLA 48H
                        $fechaServicio = strtotime($incidencia->fecha_servicio);
                        $ahora = time();
                        $diffSegundos = $fechaServicio - $ahora;

                        $puedeCancelar = $diffSegundos >= 172800;

                        // COLOR ESTADO
                        $estadoNombre = $incidencia->estado->nombre_estado;
                        switch ($estadoNombre) {
                            case 'Pendiente':
                                $estadoClase = 'bg-warning text-dark';
                                break;
                            case 'Asignada':
                                $estadoClase = 'bg-primary';
                                break;
                            case 'Finalizada':
                                $estadoClase = 'bg-success';
                                break;
                            case 'Cancelada':
                                $estadoClase = 'bg-danger';
                                break;
                            default:
                                $estadoClase = 'bg-secondary';
                        }
                    @endphp

                    <tr class="{{ $filaClase }}">

                        <td><strong>{{ $incidencia->localizador }}</strong></td>

                        <td>{{ $incidencia->especialidad->nombre_especialidad }}</td>

                        <!-- ESTADO -->
                        <td>
                            <span class="badge {{ $estadoClase }}">
                                {{ $estadoNombre }}
                            </span>
                        </td>

                        <!-- FECHA -->
                        <td>
                            {{ \Carbon\Carbon::parse($incidencia->fecha_servicio)->format('d/m/Y') }}<br>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($incidencia->fecha_servicio)->format('H:i') }}
                            </small>
                        </td>

                        <td>{{ $incidencia->direccion }}</td>

                        <!-- URGENCIA -->
                        <td>
                            <span class="badge {{ $urgente ? 'bg-danger' : 'bg-success' }}">
                                {{ $urgenciaTexto }}
                            </span>
                        </td>

                        <!-- ACCIONES -->
                        <td>

                            @if($estadoNombre === 'Pendiente' && $puedeCancelar)
                                <form method="POST" action="{{ url('/cliente/cancelar-incidencia') }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $incidencia->id }}">

                                    <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Cancelar incidencia?')">
                                        ✖ Cancelar
                                    </button>
                                </form>

                            @elseif($estadoNombre === 'Pendiente' && !$puedeCancelar)
                                <span class="text-muted small">
                                    ⏳ Menos de 48h
                                </span>

                            @else

                                <span class="text-muted small">No disponible</span>

                            @endif

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>
        </div>

    @endif

</div>
@endsection
