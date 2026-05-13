@extends('layouts.app')

@section('title', 'Dashboard Gestora - ReparaYa')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Bienvenido/a, {{ $nombre }}</h2>
        <p class="text-muted">Panel de gestora - Resumen de actividad</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="stat-card bg-primary">
            <div class="stat-number">{{ $serviciosMes }}</div>
            <div class="stat-label">Servicios este mes</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-success">
            <div class="stat-number">{{ number_format($comisionesMes, 2) }} &euro;</div>
            <div class="stat-label">Comisiones este mes</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-warning">
            <div class="stat-number">{{ number_format($totalPendiente, 2) }} &euro;</div>
            <div class="stat-label">Total pendiente de cobro</div>
        </div>
    </div>
</div>

<div class="row mt-4 g-4">
    <div class="col-md-6">
        <div class="card h-100 shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-body text-center py-4">
                <div class="fs-1 mb-2">📋</div>
                <h5 class="card-title">Crear Aviso</h5>
                <p class="card-text text-muted">Crear un nuevo aviso de reparación para un residente</p>
                <a href="{{ url('/gestora/crear-aviso') }}" class="btn btn-primary">Nuevo Aviso</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100 shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-body text-center py-4">
                <div class="fs-1 mb-2">💰</div>
                <h5 class="card-title">Liquidaciones</h5>
                <p class="card-text text-muted">Consulta tus comisiones y liquidaciones mensuales</p>
                <a href="{{ url('/gestora/liquidaciones') }}" class="btn btn-primary">Ver Liquidaciones</a>
            </div>
        </div>
    </div>
</div>
@endsection
