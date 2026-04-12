<div class="container mt-4">

    <h2 class="mb-4">Gestión de incidencias</h2>

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

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Localizador</th>
                <th>Cliente</th>
                <th>Servicio</th>
                <th>Estado</th>
                <th>Técnico</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($incidencias as $i): ?>
            <tr>
                <td><?= $i['localizador'] ?></td>
                <td><?= $i['cliente_nombre'] ?></td>
                <td><?= $i['nombre_especialidad'] ?></td>
                <td><?= $i['nombre_estado'] ?></td>
                <td><?= $i['tecnico_nombre'] ?? 'Sin asignar' ?></td>
                <td>

                    <!-- asignar -->
                    <form method="POST" action="/admin/asignar-tecnico" class="mb-2">
                        <input type="hidden" name="incidencia_id" value="<?= $i['id'] ?>">

                        <select name="tecnico_id" class="form-control mb-2">
                            <?php foreach ($tecnicos as $t): ?>
                                <option value="<?= $t['id'] ?>">
                                    <?= $t['nombre_completo'] ?> (<?= $t['nombre_especialidad'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <button class="btn btn-primary btn-sm">Asignar</button>
                    </form>

                    <!-- editar -->
                    <a href="/admin/editar-incidencia?id=<?= $i['id'] ?>" class="btn btn-warning btn-sm">
                        Editar
                    </a>

                    <!-- cancelar -->
                    <form method="POST" action="/admin/cancelar-incidencia" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $i['id'] ?>">
                        <button class="btn btn-danger btn-sm">Cancelar</button>
                    </form>

                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

</div>