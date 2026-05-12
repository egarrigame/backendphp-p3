@extends('layouts.app')

@section('title', 'Dashboard Cliente - ReparaYa')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Bienvenido/a, {{ $nombre }}</h2>
        <p class="text-muted">Panel de cliente - Gestiona tus avisos de reparación</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <h5 class="card-title">Mis Avisos</h5>
                <p class="card-text">Consulta el estado de tus incidencias</p>
                <a href="{{ url('/producto3/cliente/mis-avisos') }}" class="btn btn-primary">Ver Avisos</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <h5 class="card-title">Nueva Incidencia</h5>
                <p class="card-text">Crea un nuevo aviso de reparación</p>
                <a href="{{ url('/producto3/cliente/nueva-incidencia') }}" class="btn btn-primary">Crear Incidencia</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <h5 class="card-title">Mi Perfil</h5>
                <p class="card-text">Actualiza tus datos personales</p>
                <a href="{{ url('/producto3/perfil') }}" class="btn btn-primary">Ver Perfil</a>
            </div>
        </div>
    </div>
</div>
@endsection
