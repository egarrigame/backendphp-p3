<div class="container mt-4">

    <h2 class="mb-4">Calendario de incidencias</h2>

    <?php if (empty($incidencias)): ?>
        <div class="alert alert-info">
            No hay incidencias registradas.
        </div>
    <?php else: ?>

          <!-- TABLA -->
        <h4 class="mb-3">Listado de incidencias</h4>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Localizador</th>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Urgencia</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($incidencias as $i): ?>

                    <?php
                        $color = $i['tipo_urgencia'] === 'Urgente'
                            ? 'table-danger'
                            : 'table-success';
                    ?>

                    <tr class="<?= $i['tipo_urgencia'] === 'Urgente' ? 'fila-urgente' : 'fila-estandar' ?>">
                        <td><?= date('d/m/Y H:i', strtotime($i['fecha_servicio'])) ?></td>
                        <td><?= $i['localizador'] ?></td>
                        <td><?= $i['cliente_nombre'] ?></td>
                        <td><?= $i['nombre_especialidad'] ?></td>
                        <td><?= $i['tipo_urgencia'] ?></td>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>

        <!-- LEYENDA -->
        <div class="mt-3">
            <span class="badge bg-danger">Urgente</span>
            <span class="badge bg-success">Estándar</span>
        </div>

        <!-- SELECTOR VISTA -->
        <div class="mb-3">
            <select id="selectorVista" class="form-control">
                <option value="mes">Mes</option>
                <option value="semana">Semana</option>
                <option value="dia">Día</option>
            </select>
        </div>

        <!-- SELECTOR DE MES -->
        <div class="mb-3">
            <input type="month" id="selectorMes" class="form-control">
        </div>

        <!-- CALENDARIO -->
        <div id="calendario" class="row g-2 mb-5"></div>

    <?php endif; ?>

</div>

<!-- MODAL DETALLE INCIDENCIA -->
<div class="modal fade" id="modalIncidencia" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detalle de incidencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="modalContenido">
                <!-- contenido dinámico -->
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

<!-- PASAR DATOS A JS -->
<script>
    const incidencias = <?= json_encode($incidencias) ?>;
</script>

<script src="/assets/js/calendar.js"></script>