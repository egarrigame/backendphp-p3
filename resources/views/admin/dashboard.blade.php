@extends('layouts.app')

@section('title', 'ReparaYa - Panel Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Panel de Administración</h1>
            <p class="text-muted mb-0">
                Bienvenido, <strong>{{ session('user.nombre') }}</strong>
            </p>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-body d-flex flex-column">
                    <h5>🛠️ Crear incidencia</h5>
                    <p class="text-muted">Registrar una nueva incidencia manualmente.</p>
                    <a href="{{ url('admin/crear-incidencia') }}" class="btn btn-primary w-100 mt-auto">
                        Crear
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-body d-flex flex-column">
                    <h5>📋 Gestión de incidencias</h5>
                    <p class="text-muted">Asignar técnicos y controlar estados.</p>
                    <a href="{{ url('admin/incidencias') }}" class="btn btn-warning w-100 mt-auto">
                        Gestionar
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-body d-flex flex-column">
                    <h5>📅 Calendario</h5>
                    <p class="text-muted">Visualizar incidencias por fecha.</p>
                    <a href="{{ url('admin/calendario') }}" class="btn btn-success w-100 mt-auto">
                        Ver calendario
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-body d-flex flex-column">
                    <h5>👷 Técnicos</h5>
                    <p class="text-muted">Gestionar técnicos disponibles.</p>
                    <a href="{{ url('tecnicos') }}" class="btn btn-secondary w-100 mt-auto">
                        Ver técnicos
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-body d-flex flex-column">
                    <h5>⚙️ Especialidades</h5>
                    <p class="text-muted">Configurar tipos de servicio.</p>
                    <a href="{{ url('especialidades') }}" class="btn btn-info w-100 mt-auto">
                        Gestionar
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-body d-flex flex-column">
                    <h5>🏢 Gestoras</h5>
                    <p class="text-muted">Empresas gestoras B2B y comisiones.</p>
                    <a href="{{ url('admin/gestoras') }}" class="btn btn-dark w-100 mt-auto">
                        Ver gestoras
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-body d-flex flex-column">
                    <h5>💰 Liquidación Mensual</h5>
                    <p class="text-muted">Comisiones y pagos pendientes.</p>
                    <a href="{{ url('admin/liquidacion-mensual') }}" class="btn btn-outline-primary w-100 mt-auto">
                        Ver liquidación
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-body d-flex flex-column">
                    <h5>🚪 Sesión</h5>
                    <p class="text-muted">Cerrar sesión del sistema.</p>
                    <a href="{{ url('logout') }}" class="btn btn-danger w-100 mt-auto">
                        Cerrar sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
