<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📋 Gestión de incidencias</h2>
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

    <!-- TABLA -->
    <div class="table-responsive">
        <table class="table table-hover align-middle">

            <thead>
                <tr>
                    <th>Localizador</th>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Estado</th>
                    <th>Técnico</th>
                    <th>Urgencia</th>
                    <th>Fecha</th>
                    <th style="min-width: 220px;">Acciones</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach ($incidencias as $i): ?>

                <?php
                    $urgente = $i['tipo_urgencia'] === 'urgente';
                    $urgenciaTexto = $urgente ? 'Urgente' : 'Estándar';

                    // color fila
                    $filaClase = $urgente ? 'fila-urgente' : 'fila-estandar';
                ?>

                <tr class="<?= $filaClase ?>">

                    <td><strong><?= $i['localizador'] ?></strong></td>
                    <td><?= $i['cliente_nombre'] ?></td>
                    <td><?= $i['nombre_especialidad'] ?></td>

                    <!-- ESTADO -->
<?php
    $estado = $i['nombre_estado'];

    switch ($estado) {
        case 'Pendiente':
            $estadoClase = 'bg-warning text-dark';
            break;
        case 'Asignada':
            $estadoClase = 'bg-primary';
            break;
        case 'Finalizada':
            $estadoClase = 'bg-success';
            break;
        case 'Cancelada':
            $estadoClase = 'bg-danger';
            break;
        default:
            $estadoClase = 'bg-secondary';
    }
?>

<td>
    <span class="badge <?= $estadoClase ?>">
        <?= $estado ?>
    </span>
</td>

                    <td><?= $i['tecnico_nombre'] ?? 'Sin asignar' ?></td>

                    <!-- URGENCIA -->
                    <td>
                        <span class="badge <?= $urgente ? 'bg-danger' : 'bg-success' ?>">
                            <?= $urgenciaTexto ?>
                        </span>
                    </td>

                    <!-- FECHA -->
                    <td>
                        <?= date('d/m/Y', strtotime($i['fecha_servicio'])) ?><br>
                        <small class="text-muted">
                            <?= date('H:i', strtotime($i['fecha_servicio'])) ?>
                        </small>
                    </td>

                    <td>

                        <!-- ASIGNAR -->
                        <?php if (in_array($i['nombre_estado'], ['Pendiente', 'Asignada'])): ?>
                            <form method="POST" action="/admin/asignar-tecnico" class="mb-2 d-flex gap-1">
                                <input type="hidden" name="incidencia_id" value="<?= $i['id'] ?>">

                                <select name="tecnico_id" class="form-select form-select-sm">
                                    <?php foreach ($tecnicos as $t): ?>
                                        <option value="<?= $t['id'] ?>">
                                            <?= $t['nombre_completo'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <button class="btn btn-primary btn-sm">
                                    ✔
                                </button>
                            </form>
                        <?php endif; ?>

                        <!-- BOTONES -->
                        <div class="d-flex gap-1">

                            <a href="/admin/editar-incidencia?id=<?= $i['id'] ?>"
                               class="btn btn-warning btn-sm">
                                ✏️
                            </a>

                            <?php if ($i['nombre_estado'] !== 'Cancelada'): ?>
                                <form method="POST" action="/admin/cancelar-incidencia">
                                    <input type="hidden" name="id" value="<?= $i['id'] ?>">
                                    <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Cancelar incidencia?')">
                                        ✖
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted small">Cancelada</span>
                            <?php endif; ?>

                        </div>

                    </td>
                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>
    </div>

</div>