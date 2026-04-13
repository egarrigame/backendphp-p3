<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">👤 Mi perfil</h2>
    </div>

    <!-- ALERTAS -->
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- FORM -->
    <div class="card p-4">

        <form method="POST" action="/perfil">

            <div class="row">

                <!-- NOMBRE -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text"
                           name="nombre"
                           class="form-control"
                           value="<?= htmlspecialchars($user['nombre']) ?>"
                           required>
                </div>

                <!-- EMAIL -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           value="<?= htmlspecialchars($user['email']) ?>"
                           required>
                </div>

                <!-- TELÉFONO -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text"
                           name="telefono"
                           class="form-control"
                           value="<?= htmlspecialchars($user['telefono']) ?>"
                           required>
                </div>

                <!-- PASSWORD -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nueva contraseña</label>
                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Opcional">
                </div>

            </div>

            <!-- BOTONES -->
            <div class="d-flex justify-content-end gap-2 mt-3">

                <a href="javascript:history.back()" class="btn btn-secondary">
                    Volver
                </a>

                <button type="submit" class="btn btn-primary">
                    Guardar cambios
                </button>

            </div>

        </form>

    </div>

</div>