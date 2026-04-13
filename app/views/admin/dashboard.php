<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Panel de Administración</h1>
            <p class="text-muted mb-0">
                Bienvenido, <strong><?= $_SESSION['user']['nombre']; ?></strong>
            </p>
        </div>
    </div>

    <!-- ALERTAS -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- GRID -->
    <div class="row g-3">

        <!-- Crear incidencia -->
        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h5>🛠️ Crear incidencia</h5>
                    <p class="text-muted">Registrar una nueva incidencia manualmente.</p>
                    <a href="/admin/crear-incidencia" class="btn btn-primary w-100">
                        Crear
                    </a>
                </div>
            </div>
        </div>

        <!-- Gestión -->
        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h5>📋 Gestión de incidencias</h5>
                    <p class="text-muted">Asignar técnicos y controlar estados.</p>
                    <a href="/admin/incidencias" class="btn btn-warning w-100">
                        Gestionar
                    </a>
                </div>
            </div>
        </div>

        <!-- Calendario -->
        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h5>📅 Calendario</h5>
                    <p class="text-muted">Visualizar incidencias por fecha.</p>
                    <br>
                    <a href="/admin/calendario" class="btn btn-success w-100">
                        Ver calendario
                    </a>
                </div>
            </div>
        </div>

        <!-- Técnicos -->
        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h5>👷 Técnicos</h5>
                    <p class="text-muted">Gestionar técnicos disponibles.</p>
                    <a href="/tecnicos" class="btn btn-secondary w-100">
                        Ver técnicos
                    </a>
                </div>
            </div>
        </div>

        <!-- Especialidades -->
        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h5>⚙️ Especialidades</h5>
                    <p class="text-muted">Configurar tipos de servicio.</p>
                    <a href="/especialidades" class="btn btn-info w-100">
                        Gestionar
                    </a>
                </div>
            </div>
        </div>

        <!-- Logout -->
        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h5>🚪 Sesión</h5>
                    <p class="text-muted">Cerrar sesión del sistema.</p>
                    <a href="/logout" class="btn btn-danger w-100">
                        Cerrar sesión
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>