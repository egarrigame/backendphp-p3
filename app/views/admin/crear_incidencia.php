<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">➕ Crear nueva incidencia</h2>
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

        <form method="POST" action="/admin/crear-incidencia">

            <div class="row">

                <!-- CLIENTE -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Cliente</label>
                    <select name="cliente_id" class="form-select" required>
                        <option value="">Seleccionar cliente</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['id'] ?>">
                                <?= $c['nombre'] ?> (<?= $c['email'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- ESPECIALIDAD -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Servicio</label>
                    <select name="especialidad_id" class="form-select" required>
                        <?php foreach ($especialidades as $e): ?>
                            <option value="<?= $e['id'] ?>">
                                <?= $e['nombre_especialidad'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- FECHA -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Fecha del servicio</label>
                    <input type="datetime-local"
                           name="fecha_servicio"
                           class="form-control"
                           required>
                </div>

                <!-- URGENCIA -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipo de servicio</label>
                    <select name="tipo_urgencia" class="form-select" required>
                        <option value="estandar">Estándar</option>
                        <option value="urgente">Urgente</option>
                    </select>
                </div>

                <!-- DIRECCIÓN -->
                <div class="col-12 mb-3">
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

                <a href="/admin/dashboard" class="btn btn-secondary">
                    Volver
                </a>

                <button class="btn btn-primary">
                    Crear incidencia
                </button>

            </div>

        </form>

    </div>

</div>