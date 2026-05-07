@extends('layouts.app')

@section('content')
    <h1>Gestión de Incidencias</h1>

    <a href="{{ route('incidencias.create') }}" class="btn btn-primary mb-3">+ Nueva Incidencia</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Localizador</th>
                <th>Cliente</th>
                <th>Especialidad</th>
                <th>Descripción</th>
                <th>Fecha Servicio</th>
                <th>Urgencia</th>
                <th>Estado</th>
                <th>Técnico</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incidencias as $incidencia)
            <tr>
                <td>{{ $incidencia->localizador }} </td>
                <td>{{ $incidencia->cliente->nombre ?? '-' }} </td>
                <td>{{ $incidencia->especialidad->nombre_especialidad ?? '-' }} </td>
                <td>{{ Str::limit($incidencia->descripcion, 50) }} </td>
                <td>{{ $incidencia->fecha_servicio }} </td>
                <td>
                    @if($incidencia->tipo_urgencia == 'Urgente')
                        🔴 Urgente
                    @else
                        🟢 Estándar
                    @endif
                </td>
                <td>{{ $incidencia->estado }} </td>
                <td>{{ $incidencia->tecnico->nombre_completo ?? 'Sin asignar' }} </td>
                <td>
                    <a href="{{ route('incidencias.edit', $incidencia) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('incidencias.destroy', $incidencia) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection