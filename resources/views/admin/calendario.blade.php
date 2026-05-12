@extends('layouts.app')

@section('title', 'ReparaYa - Calendario')

@section('content')
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📅 Calendario de incidencias</h2>
    </div>

    @if($incidencias->isEmpty())
        <div class="alert alert-info">
            No hay incidencias registradas.
        </div>
    @else
        <!-- SELECTORES -->
        <div class="row mb-3">
            <div class="col-md-3">
                <select id="selectorVista" class="form-select">
                    <option value="mes">Mes</option>
                    <option value="semana">Semana</option>
                    <option value="dia">Día</option>
                </select>
            </div>

            <div class="col-md-3">
                <input type="month" id="selectorMes" class="form-control">
            </div>
        </div>

        <!-- CALENDARIO -->
        <div id="calendario" class="row g-2 mb-4"></div>

        <!-- LEYENDA -->
        <div class="mb-4 d-flex gap-2 align-items-center">
            <strong class="me-2">Urgencia:</strong>
            <span class="badge bg-danger">Urgente</span>
            <span class="badge bg-success">Estándar</span>
            <strong class="ms-3 me-2">Estado:</strong>
            <span class="badge bg-warning text-dark">Pendiente</span>
            <span class="badge bg-primary">Asignada</span>
            <span class="badge bg-success">Finalizada</span>
            <span class="badge bg-danger">Cancelada</span>
        </div>

        <!-- TABLA -->
        <h4 class="mb-3">📋 Listado de incidencias</h4>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Localizador</th>
                        <th>Cliente</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                        <th>Urgencia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($incidencias as $incidencia)
                        @php
                            $urgente = strtolower($incidencia->tipo_urgencia) === 'urgente';
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
                            <td>
                                {{ $incidencia->fecha_servicio ? \Carbon\Carbon::parse($incidencia->fecha_servicio)->format('d/m/Y') : '—' }}<br>
                                <small class="text-muted">
                                    {{ $incidencia->fecha_servicio ? \Carbon\Carbon::parse($incidencia->fecha_servicio)->format('H:i') : '' }}
                                </small>
                            </td>
                            <td><strong>{{ $incidencia->localizador }}</strong></td>
                            <td>{{ $incidencia->cliente->nombre ?? 'N/A' }}</td>
                            <td>{{ $incidencia->especialidad->nombre_especialidad ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $estadoClase }}">{{ $estado }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $urgente ? 'bg-danger' : 'bg-success' }}">
                                    {{ $urgente ? 'Urgente' : 'Estándar' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- MODAL -->
    <div class="modal fade" id="modalIncidencia" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle de incidencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalContenido"></div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const incidencias = @json($incidenciasJson);
    </script>
    <script src="{{ asset('js/calendar.js') }}"></script>
@endsection
