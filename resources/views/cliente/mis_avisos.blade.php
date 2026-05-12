@extends('layouts.app')

@section('title', 'Mis Avisos - ReparaYa')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2>Mis Avisos</h2>
        <a href="{{ url('/producto3/cliente/nueva-incidencia') }}" class="btn btn-primary">Nueva Incidencia</a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if($incidencias->isEmpty())
            <div class="alert alert-info">
                No tienes incidencias registradas. <a href="{{ url('/producto3/cliente/nueva-incidencia') }}">Crea tu primera incidencia</a>.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Localizador</th>
                            <th>Especialidad</th>
                            <th>Fecha Servicio</th>
                            <th>Estado</th>
                            <th>Urgencia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incidencias as $incidencia)
                            <tr class="row-{{ $incidencia->tipo_urgencia }}">
                                <td>{{ $incidencia->localizador }}</td>
                                <td>{{ $incidencia->especialidad->nombre_especialidad }}</td>
                                <td>{{ \Carbon\Carbon::parse($incidencia->fecha_servicio)->format('d/m/Y H:i') }}</td>
                                <td>
                                    @php
                                        $estadoNombre = $incidencia->estado->nombre_estado;
                                        $badgeClass = match($estadoNombre) {
                                            'Pendiente' => 'badge-pendiente',
                                            'Asignada' => 'badge-asignada',
                                            'Finalizada' => 'badge-finalizada',
                                            'Cancelada' => 'badge-cancelada',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $estadoNombre }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $incidencia->tipo_urgencia === 'urgente' ? 'bg-danger' : 'bg-success' }}">
                                        {{ ucfirst($incidencia->tipo_urgencia) }}
                                    </span>
                                </td>
                                <td>
                                    @if($estadoNombre !== 'Cancelada' && $estadoNombre !== 'Finalizada')
                                        <form action="{{ url('/producto3/cliente/cancelar-incidencia') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $incidencia->id }}">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de cancelar esta incidencia?')">
                                                Cancelar
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
