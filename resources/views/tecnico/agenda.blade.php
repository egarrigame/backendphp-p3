@extends('layouts.app')

@section('title', 'Mi Agenda - ReparaYa')

@section('content')
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">🧰 Mi agenda</h2>
    </div>

    <p class="mb-4">
        Bienvenido, <strong>{{ session('user.nombre') }}</strong>
    </p>

    @if(!$tecnico)
        <div class="alert alert-warning">
            No se encontró un perfil de técnico asociado a tu cuenta.
        </div>
    @elseif($incidencias->isEmpty())
        <div class="alert alert-info">
            No tienes incidencias asignadas.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Localizador</th>
                        <th>Cliente</th>
                        <th>Contacto</th>
                        <th>Servicio</th>
                        <th>Dirección</th>
                        <th>Urgencia</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($incidencias as $incidencia)
                        @php
                            $urgente = strtolower($incidencia->tipo_urgencia) === 'urgente';
                            $filaClase = $urgente ? 'fila-urgente' : 'fila-estandar';
                            $estado = $incidencia->estado->nombre_estado ?? '';
                            $estadoClass = match($estado) {
                                'Pendiente' => 'bg-warning text-dark',
                                'Asignada' => 'bg-primary',
                                'Finalizada' => 'bg-success',
                                'Cancelada' => 'bg-danger',
                                default => 'bg-secondary',
                            };
                        @endphp
                        <tr class="{{ $filaClase }}">
                            <td>
                                {{ $incidencia->fecha_servicio ? $incidencia->fecha_servicio->format('d/m/Y') : '—' }}<br>
                                <small class="text-muted">
                                    {{ $incidencia->fecha_servicio ? $incidencia->fecha_servicio->format('H:i') : '' }}
                                </small>
                            </td>
                            <td><strong>{{ $incidencia->localizador }}</strong></td>
                            <td>{{ $incidencia->cliente->nombre ?? 'N/A' }}</td>
                            <td>
                                @if($incidencia->cliente && $incidencia->cliente->telefono)
                                    <a href="tel:{{ $incidencia->cliente->telefono }}" class="btn btn-sm btn-outline-primary">
                                        📞 {{ $incidencia->cliente->telefono }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $incidencia->especialidad->nombre_especialidad ?? 'N/A' }}</td>
                            <td>{{ $incidencia->direccion }}</td>
                            <td>
                                <span class="badge {{ $urgente ? 'bg-danger' : 'bg-success' }}">
                                    {{ $urgente ? 'Urgente' : 'Estándar' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $estadoClass }}">{{ $estado }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
