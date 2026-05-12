@extends('layouts.app')

@section('title', 'Mi Agenda - ReparaYa')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Mi Agenda</h2>
        <p class="text-muted">Incidencias asignadas</p>
    </div>
</div>

@if($tecnico)
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Localizador</th>
                        <th>Especialidad</th>
                        <th>Descripción</th>
                        <th>Dirección</th>
                        <th>Fecha Servicio</th>
                        <th>Urgencia</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incidencias as $incidencia)
                        <tr class="{{ $incidencia->tipo_urgencia === 'urgente' ? 'table-danger' : 'table-success' }}">
                            <td><strong>{{ $incidencia->localizador }}</strong></td>
                            <td>{{ $incidencia->especialidad ? $incidencia->especialidad->nombre_especialidad : 'N/A' }}</td>
                            <td>{{ Str::limit($incidencia->descripcion, 50) }}</td>
                            <td>{{ $incidencia->direccion }}</td>
                            <td>{{ $incidencia->fecha_servicio ? \Carbon\Carbon::parse($incidencia->fecha_servicio)->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $incidencia->tipo_urgencia === 'urgente' ? 'bg-danger' : 'bg-success' }}">
                                    {{ ucfirst($incidencia->tipo_urgencia) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($incidencia->estado?->nombre_estado) {
                                        'Pendiente' => 'bg-warning',
                                        'Asignada' => 'bg-primary',
                                        'Finalizada' => 'bg-success',
                                        'Cancelada' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ $incidencia->estado ? $incidencia->estado->nombre_estado : 'N/A' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No tienes incidencias asignadas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="alert alert-warning">
    No se encontró un perfil de técnico asociado a tu cuenta.
</div>
@endif
@endsection
