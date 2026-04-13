<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📋 Mis avisos</h2>

        <a href="/cliente/nueva-incidencia" class="btn btn-primary">
            ➕ Nueva incidencia
        </a>
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

    <?php if (empty($avisos)): ?>
        <div class="alert alert-info">
            No tienes incidencias registradas.
        </div>
    <?php else: ?>

        <!-- TABLA PRO -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>Localizador</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Dirección</th>
                        <th>Urgencia</th>
                        <th style="min-width: 140px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($avisos as $aviso): ?>

                    <?php
                        $urgente = strtolower($aviso['tipo_urgencia']) === 'urgente';
                        $urgenciaTexto = $urgente ? 'Urgente' : 'Estándar';

                        // COLOR FILA
                        $filaClase = $urgente ? 'fila-urgente' : 'fila-estandar';

                        // REGLA 48H
                        $fechaServicio = strtotime($aviso['fecha_servicio']);
                        $ahora = time();
                        $diffSegundos = $fechaServicio - $ahora;

                        $puedeCancelar = $diffSegundos >= 172800;

                        // COLOR ESTADO
                        switch ($aviso['nombre_estado']) {
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

                    <tr class="<?= $filaClase ?>">

                        <td><strong><?= $aviso['localizador'] ?></strong></td>

                        <td><?= $aviso['nombre_especialidad'] ?></td>

                        <!-- ESTADO -->
                        <td>
                            <span class="badge <?= $estadoClase ?>">
                                <?= $aviso['nombre_estado'] ?>
                            </span>
                        </td>

                        <!-- FECHA -->
                        <td>
                            <?= date('d/m/Y', strtotime($aviso['fecha_servicio'])) ?><br>
                            <small class="text-muted">
                                <?= date('H:i', strtotime($aviso['fecha_servicio'])) ?>
                            </small>
                        </td>

                        <td><?= $aviso['direccion'] ?></td>

                        <!-- URGENCIA -->
                        <td>
                            <span class="badge <?= $urgente ? 'bg-danger' : 'bg-success' ?>">
                                <?= $urgenciaTexto ?>
                            </span>
                        </td>

                        <!-- ACCIONES -->
                        <td>

                            <?php if ($aviso['nombre_estado'] === 'Pendiente' && $puedeCancelar): ?>

                                <form method="POST" action="/cliente/cancelar-incidencia">
                                    <input type="hidden" name="id" value="<?= $aviso['id'] ?>">

                                    <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Cancelar incidencia?')">
                                        ✖ Cancelar
                                    </button>
                                </form>

                            <?php elseif ($aviso['nombre_estado'] === 'Pendiente' && !$puedeCancelar): ?>

                                <span class="text-muted small">
                                    ⏳ Menos de 48h
                                </span>

                            <?php else: ?>

                                <span class="text-muted small">No disponible</span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>
        </div>

    <?php endif; ?>

</div>