<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">✏️ Editar incidencia</h2>
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
        <form method="POST" action="/admin/actualizar-incidencia">

            <input type="hidden" name="id" value="<?= $incidencia['id'] ?>">

            <div class="row">

                <!-- CLIENTE -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Cliente</label>
                    <input type="text" class="form-control"
                           value="<?= $incidencia['cliente_nombre'] ?>" disabled>
                </div>

                <!-- ESTADO -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado_id" class="form-select">
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?= $estado['id'] ?>"
                                <?= $estado['id'] == $incidencia['estado_id'] ? 'selected' : '' ?>>
                                <?= $estado['nombre_estado'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- ESPECIALIDAD -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Especialidad</label>
                    <select name="especialidad_id" class="form-select">
                        <?php foreach ($especialidades as $e): ?>
                            <option value="<?= $e['id'] ?>"
                                <?= $e['id'] == $incidencia['especialidad_id'] ? 'selected' : '' ?>>
                                <?= $e['nombre_especialidad'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- URGENCIA -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipo de servicio</label>
                    <select name="tipo_urgencia" class="form-select">
                        <option value="estandar"
                            <?= $incidencia['tipo_urgencia'] === 'estandar' ? 'selected' : '' ?>>
                            Estándar
                        </option>
                        <option value="urgente"
                            <?= $incidencia['tipo_urgencia'] === 'urgente' ? 'selected' : '' ?>>
                            Urgente
                        </option>
                    </select>
                </div>

                <!-- FECHA -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Fecha del servicio</label>
                    <input type="datetime-local" name="fecha_servicio"
                           class="form-control"
                           value="<?= date('Y-m-d\TH:i', strtotime($incidencia['fecha_servicio'])) ?>">
                </div>

                <!-- DIRECCIÓN -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control"
                           value="<?= $incidencia['direccion'] ?>" required>
                </div>

                <!-- DESCRIPCIÓN -->
                <div class="col-12 mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="4" required><?= trim($incidencia['descripcion']) ?></textarea>
                </div>

            </div>

            <!-- BOTONES -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="/admin/incidencias" class="btn btn-secondary">
                    ← Volver
                </a>
                <button class="btn btn-primary">
                    💾 Guardar cambios
                </button>
            </div>

        </form>
    </div>

</div>