<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">🛠️ Solicitar servicio</h2>
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

        <form method="POST" action="/cliente/nueva-incidencia">

            <div class="row">

                <!-- SERVICIO -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipo de servicio</label>
                    <select name="especialidad_id" class="form-select" required>
                        <option value="">Selecciona una opción</option>
                        <?php foreach ($especialidades as $esp): ?>
                            <option value="<?= $esp['id'] ?>">
                                <?= $esp['nombre_especialidad'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- URGENCIA -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipo de urgencia</label>
                    <select name="tipo_urgencia" class="form-select" required>
                        <option value="">Selecciona una opción</option>
                        <option value="estandar">Estándar</option>
                        <option value="urgente">Urgente</option>
                    </select>
                </div>

                <!-- FECHA -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Fecha del servicio</label>
                    <input type="datetime-local"
                           name="fecha_servicio"
                           class="form-control"
                           required>
                    <small class="text-muted">
                        Los servicios estándar requieren 48h de antelación
                    </small>
                </div>

                <!-- DIRECCIÓN -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text"
                           name="direccion"
                           class="form-control"
                           placeholder="Calle, número, ciudad..."
                           required>
                </div>

                <!-- DESCRIPCIÓN -->
                <div class="col-12 mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion"
                              class="form-control"
                              rows="4"
                              placeholder="Describe la avería..."
                              required></textarea>
                </div>

            </div>

            <!-- BOTONES -->
            <div class="d-flex justify-content-end gap-2 mt-3">

                <a href="/cliente/dashboard" class="btn btn-secondary">
                    Volver
                </a>

                <button type="submit" class="btn btn-primary">
                    Crear incidencia
                </button>

            </div>

        </form>

    </div>

</div>