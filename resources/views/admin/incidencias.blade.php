@extends('layouts.app')

@section('title', 'ReparaYa - Incidencias')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Incidencias</h2>
        <a href="{{ url('producto3/admin/crear-incidencia') }}" class="btn btn-primary">Nueva Incidencia</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Localizador</th>
                    <th>Cliente</th>
                    <th>Especialidad</th>
                    <th>Técnico</th>
                    <th>Estado</th>
                    <th>Urgencia</th>
                    <th>Fecha Servicio</th>
                    <th>Asignar Técnico</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incidencias as $incidencia)
                    <tr class="{{ $incidencia->tipo_urgencia === 'urgente' ? 'table-danger' : 'table-success' }}">
                        <td><strong>{{ $incidencia->localizador }}</strong></td>
                        <td>{{ $incidencia->cliente->nombre ?? 'N/A' }}</td>
                        <td>{{ $incidencia->especialidad->nombre_especialidad ?? 'N/A' }}</td>
                        <td>{{ $incidencia->tecnico->nombre_completo ?? 'Sin asignar' }}</td>
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
                        <td>
                            <span class="badge {{ $incidencia->tipo_urgencia === 'urgente' ? 'bg-danger' : 'bg-info text-dark' }}">
                                {{ ucfirst($incidencia->tipo_urgencia) }}
                            </span>
                        </td>
                        <td>{{ $incidencia->fecha_servicio ? \Carbon\Carbon::parse($incidencia->fecha_servicio)->format('d/m/Y H:i') : 'N/A' }}</td>
                        <td>
                            @if($incidencia->estado->nombre_estado !== 'Cancelada' && $incidencia->estado->nombre_estado !== 'Finalizada')
                                <form action="{{ url('producto3/admin/asignar-tecnico') }}" method="POST" class="d-flex gap-1">
                                    @csrf
                                    <input type="hidden" name="incidencia_id" value="{{ $incidencia->id }}">
                                    <select name="tecnico_id" class="form-select form-select-sm" style="min-width: 140px;" required>
                                        <option value="">Seleccionar...</option>
                                        @foreach($tecnicos as $tecnico)
                                            <option value="{{ $tecnico->id }}" {{ $incidencia->tecnico_id == $tecnico->id ? 'selected' : '' }}>
                                                {{ $tecnico->nombre_completo }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Asignar</button>
                                </form>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ url('producto3/admin/editar-incidencia/' . $incidencia->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                @if($incidencia->estado->nombre_estado !== 'Cancelada')
                                    <form action="{{ url('producto3/admin/cancelar-incidencia') }}" method="POST" onsubmit="return confirm('¿Cancelar esta incidencia?')">
                                        @csrf
                                        <input type="hidden" name="incidencia_id" value="{{ $incidencia->id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Cancelar</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No hay incidencias registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
