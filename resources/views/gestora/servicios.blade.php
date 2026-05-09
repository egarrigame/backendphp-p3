{{-- 
    VISTA: LISTADO DE SERVICIOS DE LA GESTORA
    Muestra una tabla con todos los servicios creados por esta gestora
--}}

@extends('layouts.app')

@section('content')
    <h1>Mis Servicios</h1>
    
    {{-- Botón para crear nuevo servicio --}}
    <a href="{{ route('gestora.servicios.create') }}" class="btn btn-primary mb-3">+ Nuevo Servicio</a>
    
    {{-- Mensaje flash de éxito --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    {{-- Tabla de servicios --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Precio Base</th>
                <th>Comisión</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($servicios as $servicio)
            <tr>
                <td>{{ $servicio->id }} </td>
                <td>{{ $servicio->descripcion }} </td>
                <td>{{ number_format($servicio->precio_base, 2) }} € </td>
                <td>{{ number_format($servicio->comision_aplicada, 2) }} € </td>
                <td>
                    @if($servicio->estado == 'pendiente')
                        <span class="badge bg-warning">Pendiente</span>
                    @elseif($servicio->estado == 'completado')
                        <span class="badge bg-success">Completado</span>
                    @else
                        <span class="badge bg-secondary">{{ $servicio->estado }}</span>
                    @endif
                </td>
                <td>{{ $servicio->created_at }} </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection