@extends('layouts.app')

@section('content')
    <h1>Servicios de Gestoras</h1>
    
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Gestora</th>
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
                <td>{{ $servicio->gestora_nombre }} </td>
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