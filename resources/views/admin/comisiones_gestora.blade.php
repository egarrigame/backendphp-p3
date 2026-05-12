@extends('layouts.app')

@section('title', 'ReparaYa - Comisiones de ' . $gestora->nombre)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Comisiones: {{ $gestora->nombre }}</h2>
        <a href="{{ url('producto3/admin/gestoras') }}" class="btn btn-secondary">Volver a Gestoras</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>CIF:</strong> {{ $gestora->CIF }}
                </div>
                <div class="col-md-4">
                    <strong>Porcentaje:</strong> {{ $gestora->porcentaje_comision }}%
                </div>
                <div class="col-md-4">
                    <strong>Total Pendiente:</strong> <span class="text-danger fw-bold">{{ number_format($totalPendiente, 2) }} €</span>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Localizador</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Precio Base</th>
                    <th>Comisión</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incidencias as $incidencia)
                    <tr>
                        <td><strong>{{ $incidencia->localizador }}</strong></td>
                        <td>{{ Str::limit($incidencia->descripcion, 50) }}</td>
                        <td>
                            @php
                                $estado = $incidencia->estado->nombre_estado ?? '';
                                $badgeClass = match($estado) {
                                    'Pendiente' => 'bg-warning text-dark',
                                    'Asignada' => 'bg-primary',
                                    'Finalizada' => 'bg-success',
                                    'Cancelada' => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $estado }}</span>
                        </td>
                        <td>{{ number_format($incidencia->precio_base, 2) }} €</td>
                        <td>
                            @if($incidencia->comision)
                                {{ number_format($incidencia->comision->monto_comision, 2) }} €
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No hay incidencias para esta gestora.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
