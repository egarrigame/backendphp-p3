<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📅 Calendario de incidencias</h2>
    </div>

    <?php if (empty($incidencias)): ?>
        <div class="alert alert-info">
            No hay incidencias registradas.
        </div>
    <?php else: ?>

        <!-- SELECTORES -->
        <div class="row mb-3">
            <div class="col-md-3">
                <select id="selectorVista" class="form-select">
                    <option value="mes">Mes</option>
                    <option value="semana">Semana</option>
                    <option value="dia">Día</option>
                </select>
            </div>

            <div class="col-md-3">
                <input type="month" id="selectorMes" class="form-control">
            </div>
        </div>

        <!-- CALENDARIO -->
        <!-- IMPORTANTE: flex para que semana/día no se rompan -->
        <div id="calendario" class="row g-2 mb-4"></div>

        <!-- LEYENDA (SOLO LO QUE PIDE LA RÚBRICA) -->
        <div class="mb-4 d-flex gap-2 align-items-center">
            <span class="badge bg-danger">Urgente</span>
            <span class="badge bg-success">Estándar</span>
        </div>

        <!-- TABLA -->
        <h4 class="mb-3">📋 Listado de incidencias</h4>

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Localizador</th>
                        <th>Cliente</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                        <th>Urgencia</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($incidencias as $i): ?>

                    <?php
                        $urgente = strtolower($i['tipo_urgencia']) === 'urgente';
                        $urgenciaTexto = $urgente ? 'Urgente' : 'Estándar';

                        $filaClase = $urgente ? 'fila-urgente' : 'fila-estandar';

                        switch ($i['nombre_estado']) {
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
                        <td>
                            <?= date('d/m/Y', strtotime($i['fecha_servicio'])) ?><br>
                            <small class="text-muted">
                                <?= date('H:i', strtotime($i['fecha_servicio'])) ?>
                            </small>
                        </td>

                        <td><strong><?= $i['localizador'] ?></strong></td>
                        <td><?= $i['cliente_nombre'] ?></td>
                        <td><?= $i['nombre_especialidad'] ?></td>

                        <td>
                            <span class="badge <?= $estadoClase ?>">
                                <?= $i['nombre_estado'] ?>
                            </span>
                        </td>

                        <td>
                            <span class="badge <?= $urgente ? 'bg-danger' : 'bg-success' ?>">
                                <?= $urgenciaTexto ?>
                            </span>
                        </td>
                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>
        </div>

    <?php endif; ?>

</div>

<!-- MODAL -->
<div class="modal fade" id="modalIncidencia" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detalle de incidencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="modalContenido"></div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

<!-- DATOS -->
<script>
    const incidencias = <?= json_encode($incidencias) ?>;
</script>

<script src="/assets/js/calendar.js"></script>