@extends('layouts.app')

@section('title', 'ReparaYa - Gestoras')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Gestoras</h2>
        <a href="{{ url('admin/gestoras/crear') }}" class="btn btn-primary">Nueva Gestora</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>CIF</th>
                    <th>Porcentaje Comisión</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gestoras as $gestora)
                    <tr>
                        <td><strong>{{ $gestora->nombre }}</strong></td>
                        <td>{{ $gestora->CIF }}</td>
                        <td>{{ $gestora->porcentaje_comision }}%</td>
                        <td>
                            @if($gestora->activa)
                                <span class="badge bg-success">Activa</span>
                            @else
                                <span class="badge bg-danger">Inactiva</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ url('admin/gestoras/editar/' . $gestora->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                <a href="{{ url('admin/gestoras/' . $gestora->id . '/comisiones') }}" class="btn btn-sm btn-outline-primary">Ver Comisiones</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No hay gestoras registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
