<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">🧰 Mi agenda</h2>
    </div>

    <p class="mb-4">
        Bienvenido, <strong><?= $_SESSION['user']['nombre']; ?></strong>
    </p>

    <?php if (empty($incidencias)): ?>
        <div class="alert alert-info">
            No tienes incidencias asignadas.
        </div>
    <?php else: ?>

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Localizador</th>
                        <th>Cliente</th>
                        <th>Contacto</th>
                        <th>Servicio</th>
                        <th>Dirección</th>
                        <th>Urgencia</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($incidencias as $i): ?>

                    <?php
                        $urgente = strtolower($i['tipo_urgencia']) === 'urgente';

                        // COLOR FILA
                        $filaClase = $urgente ? 'fila-urgente' : 'fila-estandar';

                        // ESTADO VISUAL CONSISTENTE
                        switch ($i['nombre_estado']) {
                            case 'Pendiente':
                                $estadoClass = 'bg-warning text-dark';
                                break;
                            case 'Asignada':
                                $estadoClass = 'bg-primary';
                                break;
                            case 'Finalizada':
                                $estadoClass = 'bg-success';
                                break;
                            case 'Cancelada':
                                $estadoClass = 'bg-danger';
                                break;
                            default:
                                $estadoClass = 'bg-secondary';
                        }
                    ?>

                    <tr class="<?= $filaClase ?>">

                        <!-- FECHA -->
                        <td>
                            <?= date('d/m/Y', strtotime($i['fecha_servicio'])) ?><br>
                            <small class="text-muted">
                                <?= date('H:i', strtotime($i['fecha_servicio'])) ?>
                            </small>
                        </td>

                        <!-- LOCALIZADOR -->
                        <td>
                            <strong><?= $i['localizador'] ?></strong>
                        </td>

                        <!-- CLIENTE -->
                        <td><?= $i['cliente_nombre'] ?></td>

                        <!-- CONTACTO -->
                        <td>
                            <a href="tel:<?= $i['cliente_telefono'] ?>"
                               class="btn btn-sm btn-outline-primary">
                                📞 <?= $i['cliente_telefono'] ?>
                            </a>
                        </td>

                        <!-- SERVICIO -->
                        <td><?= $i['nombre_especialidad'] ?></td>

                        <!-- DIRECCIÓN -->
                        <td><?= $i['direccion'] ?></td>

                        <!-- URGENCIA -->
                        <td>
                            <span class="badge <?= $urgente ? 'bg-danger' : 'bg-success' ?>">
                                <?= $urgente ? 'Urgente' : 'Estándar' ?>
                            </span>
                        </td>

                        <!-- ESTADO -->
                        <td>
                            <span class="badge <?= $estadoClass ?>">
                                <?= $i['nombre_estado'] ?>
                            </span>
                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>
        </div>

    <?php endif; ?>

</div>