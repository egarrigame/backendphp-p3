<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">🧩 Gestión de especialidades</h2>
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

    <!-- CREAR -->
    <div class="card p-3 mb-4">
        <h5 class="mb-3">➕ Nueva especialidad</h5>

        <form method="POST" action="/especialidades/guardar" class="d-flex gap-2">

            <input type="text"
                   name="nombre_especialidad"
                   class="form-control"
                   placeholder="Ej: Fontanería"
                   required>

            <button class="btn btn-primary">
                Añadir
            </button>

        </form>
    </div>

    <!-- LISTADO -->
    <?php if (empty($especialidades)): ?>

        <div class="alert alert-info">
            No hay especialidades registradas.
        </div>

    <?php else: ?>

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th style="min-width: 220px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($especialidades as $e): ?>

                    <tr>

                        <!-- ID -->
                        <td>
                            <span class="badge bg-secondary">
                                #<?= $e['id'] ?>
                            </span>
                        </td>

                        <!-- NOMBRE EDITABLE -->
                        <td>
                            <form method="POST"
                                  action="/especialidades/actualizar"
                                  class="d-flex gap-2">

                                <input type="hidden" name="id" value="<?= $e['id'] ?>">

                                <input type="text"
                                       name="nombre_especialidad"
                                       class="form-control form-control-sm"
                                       value="<?= $e['nombre_especialidad'] ?>"
                                       required>

                                <button class="btn btn-warning btn-sm">
                                    ✔
                                </button>

                            </form>
                        </td>

                        <!-- ACCIONES -->
                        <td>

                            <form method="POST"
                                  action="/especialidades/eliminar"
                                  onsubmit="return confirm('¿Eliminar esta especialidad?');">

                                <input type="hidden" name="id" value="<?= $e['id'] ?>">

                                <button class="btn btn-outline-danger btn-sm">
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