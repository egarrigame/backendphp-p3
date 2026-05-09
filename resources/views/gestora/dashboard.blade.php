@extends('layouts.app')

@section('content')
    <h1>Panel de Control - Gestora</h1>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Comisiones Acumuladas</div>
                <div class="card-body">
                    <h2 class="card-title">{{ number_format($totalComisiones, 2) }} €</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Servicios Realizados</div>
                <div class="card-body">
                    <h2 class="card-title">{{ $totalServicios }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('gestora.servicios.create') }}" class="btn btn-primary">+ Nuevo Servicio</a>
        <a href="{{ route('gestora.servicios.index') }}" class="btn btn-secondary">Mis Servicios</a>
        <a href="{{ route('gestora.comisiones') }}" class="btn btn-info">Ver Comisiones</a>
    </div>
@endsection