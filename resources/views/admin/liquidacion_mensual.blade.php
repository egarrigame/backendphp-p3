@extends('layouts.app')

@section('title', 'ReparaYa - Liquidación Mensual')

@section('content')
    <div class="mb-4">
        <h2>Liquidación Mensual</h2>
    </div>

    <div class="card mb-4">
        <div class="card-body text-center">
            <h5 class="card-title">Total Global Pendiente</h5>
            <p class="display-6 text-danger fw-bold">{{ number_format($totalGlobal, 2) }} €</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Gestora</th>
                    <th>CIF</th>
                    <th>Total Mes Actual</th>
                    <th>Total Pendiente</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($liquidaciones as $liquidacion)
                    <tr>
                        <td><strong>{{ $liquidacion->gestora->nombre }}</strong></td>
                        <td>{{ $liquidacion->gestora->CIF }}</td>
                        <td>{{ number_format($liquidacion->total_mes, 2) }} €</td>
                        <td>
                            <span class="{{ $liquidacion->total_pendiente > 0 ? 'text-danger fw-bold' : '' }}">
                                {{ number_format($liquidacion->total_pendiente, 2) }} €
                            </span>
                        </td>
                        <td>
                            <a href="{{ url('producto3/admin/gestoras/' . $liquidacion->gestora->id . '/comisiones') }}" class="btn btn-sm btn-outline-primary">Ver Detalle</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No hay gestoras activas con liquidaciones.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
