<?php extract($data ?? []); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ReparaYa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- FAVICON -->
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon-16x16.png">
    <link rel="icon" href="/assets/favicon.ico">

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">

        <!-- BRAND -->
        <?php if (isset($_SESSION['user'])): ?>
            <?php if ($_SESSION['user']['rol'] === 'admin'): ?>
                <a class="navbar-brand" href="/admin/dashboard">ReparaYa - Admin</a>
            <?php elseif ($_SESSION['user']['rol'] === 'tecnico'): ?>
                <a class="navbar-brand" href="/tecnico/agenda">ReparaYa - Técnico</a>
            <?php else: ?>
                <a class="navbar-brand" href="/cliente/dashboard">ReparaYa</a>
            <?php endif; ?>
        <?php else: ?>
            <a class="navbar-brand" href="/">ReparaYa</a>
        <?php endif; ?>

        <!-- TOGGLER -->
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            ☰
        </button>

        <div class="collapse navbar-collapse" id="nav">

            <!-- LINKS -->
            <div class="navbar-nav">

                <?php if (isset($_SESSION['user'])): ?>

                    <?php if ($_SESSION['user']['rol'] === 'admin'): ?>

                        <a class="nav-link" href="/admin/dashboard">Dashboard</a>
                        <a class="nav-link" href="/admin/incidencias">Incidencias</a>
                        <a class="nav-link" href="/admin/calendario">Calendario</a>
                        <a class="nav-link" href="/tecnicos">Técnicos</a>
                        <a class="nav-link" href="/especialidades">Especialidades</a>

                    <?php elseif ($_SESSION['user']['rol'] === 'tecnico'): ?>

                        <a class="nav-link" href="/tecnico/agenda">Mi agenda</a>

                    <?php else: ?>

                        <a class="nav-link" href="/cliente/dashboard">Dashboard</a>
                        <a class="nav-link" href="/cliente/mis-avisos">Mis avisos</a>
                        <a class="nav-link" href="/cliente/nueva-incidencia">Nueva incidencia</a>

                    <?php endif; ?>

                <?php endif; ?>

            </div>

            <!-- USER -->
            <div class="ms-auto">

                <?php if (isset($_SESSION['user'])): ?>

                    <span class="nav-link">
                        <?= htmlspecialchars($_SESSION['user']['nombre']) ?>
                    </span>

                    <a class="nav-link" href="/perfil">Perfil</a>

                    <a class="btn" href="/logout">Salir</a>

                <?php endif; ?>

            </div>

        </div>
    </div>
</nav>

<div class="container mt-4 fade-in">
    <?php require $viewPath; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>