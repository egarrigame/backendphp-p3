@extends('layouts.app')

@section('title', 'Liquidaciones - ReparaYa')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Liquidaciones</h2>
        <p class="text-muted">Resumen de comisiones agrupadas por mes</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card bg-warning">
            <div class="stat-number">{{ number_format($totalPendiente, 2) }} &euro;</div>
            <div class="stat-label">Total pendiente de cobro</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($liquidaciones->isEmpty())
                    <p class="text-muted text-center">No hay liquidaciones registradas.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Periodo</th>
                                    <th>Núm. Servicios</th>
                                    <th>Total Comisión</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($liquidaciones as $liquidacion)
                                    <tr>
                                        <td>{{ $liquidacion->periodo }}</td>
                                        <td>{{ $liquidacion->num_servicios }}</td>
                                        <td>{{ number_format($liquidacion->total_comision, 2) }} &euro;</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
