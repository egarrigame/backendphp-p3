@extends('layouts.app')

@section('content')
    <h1>Detalle de Servicios - {{ $gestora->nombre }}</h1>
    
    <a href="{{ route('admin.liquidaciones') }}" class="btn btn-secondary mb-3">← Volver a Liquidaciones</a>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Comunidad</th>
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
                <td>{{ $servicio->comunidad_nombre }} </td>
                <td>{{ $servicio->descripcion }} </td>
                <td>{{ number_format($servicio->precio_base, 2) }} € </td>
                <td>{{ number_format($servicio->comision_aplicada, 2) }} € </td>
                <td>{{ $servicio->estado }} </td>
                <td>{{ $servicio->created_at }} </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection