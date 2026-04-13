<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">🛠️ Gestión de técnicos</h2>
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

    <!-- CREAR TÉCNICO -->
    <div class="card mb-4 p-4">
        <h5 class="mb-3">➕ Nuevo técnico</h5>

        <form method="POST" action="/tecnicos/guardar">
            <div class="row g-2">

                <div class="col-md-4">
                    <input type="text" name="nombre_completo" class="form-control"
                           placeholder="Nombre completo" required>
                </div>

                <div class="col-md-4">
                    <select name="especialidad_id" class="form-select" required>
                        <option value="">Especialidad</option>
                        <?php foreach ($especialidades as $e): ?>
                            <option value="<?= $e['id'] ?>">
                                <?= $e['nombre_especialidad'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        Crear
                    </button>
                </div>

            </div>
        </form>
    </div>

    <!-- LISTADO -->
    <?php if (empty($tecnicos)): ?>

        <div class="alert alert-info">
            No hay técnicos registrados.
        </div>

    <?php else: ?>

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Especialidad</th>
                        <th>Estado</th>
                        <th style="min-width: 260px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($tecnicos as $t): ?>

                    <tr>

                        <td>
                            <strong><?= $t['nombre_completo'] ?></strong>
                        </td>

                        <td><?= $t['nombre_especialidad'] ?></td>

                        <!-- DISPONIBLE -->
                        <td>
                            <span class="badge <?= $t['disponible'] ? 'bg-success' : 'bg-secondary' ?>">
                                <?= $t['disponible'] ? 'Disponible' : 'No disponible' ?>
                            </span>
                        </td>

                        <td>

                            <!-- EDITAR -->
                            <form method="POST" action="/tecnicos/actualizar"
                                  class="d-flex gap-1 mb-2">

                                <input type="hidden" name="id" value="<?= $t['id'] ?>">

                                <select name="especialidad_id" class="form-select form-select-sm">
                                    <?php foreach ($especialidades as $e): ?>
                                        <option value="<?= $e['id'] ?>"
                                            <?= $e['id'] == $t['especialidad_id'] ? 'selected' : '' ?>>
                                            <?= $e['nombre_especialidad'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <select name="disponible" class="form-select form-select-sm">
                                    <option value="1" <?= $t['disponible'] ? 'selected' : '' ?>>
                                        Disponible
                                    </option>
                                    <option value="0" <?= !$t['disponible'] ? 'selected' : '' ?>>
                                        No disponible
                                    </option>
                                </select>

                                <button class="btn btn-warning btn-sm">
                                    ✔
                                </button>

                            </form>

                            <!-- ELIMINAR -->
                            <form method="POST" action="/tecnicos/eliminar"
                                  onsubmit="return confirm('¿Eliminar técnico?');">

                                <input type="hidden" name="id" value="<?= $t['id'] ?>">

                                <button class="btn btn-danger btn-sm w-100">
                                    ✖ Eliminar
                                </button>

                            </form>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>
            </table>
        </div>

    <?php endif; ?>

</div>