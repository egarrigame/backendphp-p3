@extends('layouts.app')

@section('content')
    <h1>Liquidaciones por Gestora</h1>
    
    <div class="alert alert-info">
        <strong>Total general a liquidar: {{ number_format($totalGeneral, 2) }} €</strong>
    </div>
    
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Gestora</th>
                <th>Email</th>
                <th>% Comisión</th>
                <th>Servicios Realizados</th>
                <th>Total Comisiones</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($liquidaciones as $liquidacion)
            <tr>
                <td>{{ $liquidacion->id }} </td>
                <td>{{ $liquidacion->nombre }} </td>
                <td>{{ $liquidacion->email }} </td>
                <td>{{ $liquidacion->comision_porcentaje }}% </td>
                <td>{{ $liquidacion->total_servicios }} </td>
                <td><strong>{{ number_format($liquidacion->total_comisiones, 2) }} €</strong> </td>
                <td>
                    <a href="{{ route('admin.detalle.gestora', $liquidacion->id) }}" class="btn btn-info btn-sm">Ver Detalle</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
