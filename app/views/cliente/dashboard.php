<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">👤 Panel de cliente</h2>
    </div>

    <p class="mb-4">
        Bienvenido, <strong><?= $_SESSION['user']['nombre']; ?></strong>
    </p>

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

    <div class="row">

        <!-- MIS AVISOS -->
        <div class="col-md-4 mb-3">
            <div class="card h-100 p-3">
                <h5>📋 Mis avisos</h5>
                <p>Consulta todas tus incidencias pasadas y futuras.</p>
                <a href="/cliente/mis-avisos" class="btn btn-primary">
                    Ver avisos
                </a>
            </div>
        </div>

        <!-- NUEVA INCIDENCIA -->
        <div class="col-md-4 mb-3">
            <div class="card h-100 p-3">
                <h5>➕ Nueva incidencia</h5>
                <p>Solicita un técnico para una avería.</p>
                <br>
                <a href="/cliente/nueva-incidencia" class="btn btn-success">
                    Crear
                </a>
            </div>
        </div>

        <!-- PERFIL -->
        <div class="col-md-4 mb-3">
            <div class="card h-100 p-3">
                <h5>👤 Perfil</h5>
                <p>Modifica tus datos personales.</p>
                <br>
                <a href="/perfil" class="btn btn-warning">
                    Editar perfil
                </a>
            </div>
        </div>

        <!-- LOGOUT -->
        <div class="col-md-4 mb-3">
            <div class="card h-100 p-3">
                <h5>🔒 Sesión</h5>
                <p>Cerrar sesión del sistema.</p>
                <a href="/logout" class="btn btn-danger">
                    Cerrar sesión
                </a>
            </div>
        </div>

    </div>

</div>