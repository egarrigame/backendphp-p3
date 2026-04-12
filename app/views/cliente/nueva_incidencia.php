<div class="row justify-content-center">
    <div class="col-md-6">

        <h2 class="mb-4 text-center">Solicitar servicio</h2>

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

        <form method="POST" action="/cliente/nueva-incidencia">

            <div class="mb-3">
                <label class="form-label">Tipo de servicio</label>
                <select name="especialidad_id" class="form-control" required>
                    <option value="">Selecciona una opción</option>
                    <?php foreach ($especialidades as $esp): ?>
                        <option value="<?= $esp['id'] ?>">
                            <?= $esp['nombre_especialidad'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Fecha del servicio</label>
                <input type="datetime-local" name="fecha_servicio" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo de urgencia</label>
                <select name="tipo_urgencia" class="form-control" required>
                    <option value="">Selecciona una opción</option>
                    <option value="estandar">Estándar</option>
                    <option value="urgente">Urgente</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Crear incidencia
            </button>

        </form>

    </div>
</div>