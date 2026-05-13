<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ReparaYa')</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            @if(session('user'))
                @if(session('user.rol') === 'admin')
                    <a class="navbar-brand" href="{{ url('admin/dashboard') }}">ReparaYa - Admin</a>
                @elseif(session('user.rol') === 'tecnico')
                    <a class="navbar-brand" href="{{ url('tecnico/agenda') }}">ReparaYa - Técnico</a>
                @else
                    <a class="navbar-brand" href="{{ url('cliente/dashboard') }}">ReparaYa</a>
                @endif
            @elseif(session('gestora'))
                <a class="navbar-brand" href="{{ url('gestora/dashboard') }}">ReparaYa - Gestora</a>
            @else
                <a class="navbar-brand" href="{{ url('') }}">ReparaYa</a>
            @endif
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                ☰
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @if(session('user'))
                        @if(session('user.rol') === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('admin/dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('admin/incidencias') }}">Incidencias</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('admin/calendario') }}">Calendario</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('tecnicos') }}">Técnicos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('especialidades') }}">Especialidades</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('admin/gestoras') }}">Gestoras</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('admin/liquidacion-mensual') }}">Liquidación Mensual</a>
                            </li>
                        @elseif(session('user.rol') === 'tecnico')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('tecnico/agenda') }}">Mi Agenda</a>
                            </li>
                        @elseif(session('user.rol') === 'particular')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('cliente/dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('cliente/mis-avisos') }}">Mis Avisos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('cliente/nueva-incidencia') }}">Nueva Incidencia</a>
                            </li>
                        @endif
                    @elseif(session('gestora'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('gestora/dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('gestora/crear-aviso') }}">Crear Aviso</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('gestora/liquidaciones') }}">Liquidaciones</a>
                        </li>
                    @endif
                </ul>
                <ul class="navbar-nav ms-auto">
                    @if(session('user'))
                        <li class="nav-item">
                            <span class="nav-link">{{ session('user.nombre') }}</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('perfil') }}">Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn" href="{{ url('logout') }}">Salir</a>
                        </li>
                    @elseif(session('gestora'))
                        <li class="nav-item">
                            <span class="nav-link">{{ session('gestora.nombre') }}</span>
                        </li>
                        <li class="nav-item">
                            <a class="btn" href="{{ url('logout') }}">Salir</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
