{{-- 
    ============================================================
    LAYOUT PRINCIPAL DE LA APLICACIÓN (app.blade.php)
    ============================================================
    Este archivo es la plantilla base que todas las vistas extienden.
    Contiene la estructura HTML común: head, navegación, footer.
    Las vistas hijas inyectan su contenido en @yield('content')
--}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReparaYa - Producto 3</title>
    
    {{-- Bootstrap 5 CSS (CDN) para estilos responsivos --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    {{-- BARRA DE NAVEGACIÓN SUPERIOR --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            {{-- Logo / nombre de la aplicación --}}
            <a class="navbar-brand" href="{{ url('/') }}">ReparaYa</a>
            
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        {{-- Enlace al CRUD de especialidades --}}
                        <a class="nav-link" href="{{ route('especialidades.index') }}">Especialidades</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- CONTENIDO PRINCIPAL --}}
    {{-- Las vistas hijas reemplazarán la sección 'content' --}}
    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    {{-- Bootstrap JavaScript (para componentes interactivos) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>