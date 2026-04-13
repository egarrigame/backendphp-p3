<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/Model.php';

class Incidencia extends Model
{
    protected string $table = 'incidencias';

    public function generarLocalizador(): string
    {
        return 'R' . strtoupper(substr(uniqid(), -6));
    }

    public function getEstadoIdByNombre(string $nombre): int
    {
        $sql = "SELECT id FROM estados WHERE nombre_estado = :nombre LIMIT 1";
        $result = $this->fetch($sql, ['nombre' => $nombre]);

        return $result ? (int)$result['id'] : 0;
    }

    // 🔥 FIX: validación real en segundos
    public function validarRegla48h(string $fecha, string $tipo): bool
    {
        if ($tipo !== 'Estándar') {
            return true;
        }

        $fechaServicio = new DateTime($fecha);
        $ahora = new DateTime();

        $diffSegundos = $fechaServicio->getTimestamp() - $ahora->getTimestamp();

        return $diffSegundos >= 172800; // 48h
    }

    public function create(array $data): bool
    {
        if (!$this->validarRegla48h($data['fecha_servicio'], $data['tipo_urgencia'])) {
            return false;
        }

        $estadoId = $this->getEstadoIdByNombre('Pendiente');

        if ($estadoId === 0) {
            return false;
        }

        $sql = "INSERT INTO {$this->table}
            (localizador, cliente_id, especialidad_id, estado_id, descripcion, direccion, fecha_servicio, tipo_urgencia)
            VALUES (:localizador, :cliente_id, :especialidad_id, :estado_id, :descripcion, :direccion, :fecha_servicio, :tipo_urgencia)";

        return $this->execute($sql, [
            'localizador' => $this->generarLocalizador(),
            'cliente_id' => $data['cliente_id'],
            'especialidad_id' => $data['especialidad_id'],
            'estado_id' => $estadoId,
            'descripcion' => $data['descripcion'],
            'direccion' => $data['direccion'],
            'fecha_servicio' => $data['fecha_servicio'],
            'tipo_urgencia' => $data['tipo_urgencia']
        ]);
    }

    // =========================
    // CLIENTE
    // =========================
    public function findByCliente(int $clienteId): array
    {
        $sql = "SELECT i.*, 
                       e.nombre_estado, 
                       s.nombre_especialidad
                FROM incidencias i
                JOIN estados e ON i.estado_id = e.id
                JOIN especialidades s ON i.especialidad_id = s.id
                WHERE i.cliente_id = :cliente_id
                ORDER BY i.fecha_servicio DESC";

        return $this->fetchAll($sql, [
            'cliente_id' => $clienteId
        ]);
    }

    // =========================
    // ADMIN
    // =========================
    public function findAll(): array
    {
        $sql = "SELECT i.*, 
                       u.nombre AS cliente_nombre,
                       u.telefono AS cliente_telefono,
                       t.nombre_completo AS tecnico_nombre,
                       e.nombre_estado,
                       s.nombre_especialidad
                FROM incidencias i
                JOIN usuarios u ON i.cliente_id = u.id
                LEFT JOIN tecnicos t ON i.tecnico_id = t.id
                JOIN estados e ON i.estado_id = e.id
                JOIN especialidades s ON i.especialidad_id = s.id
                ORDER BY i.fecha_servicio DESC";

        return $this->fetchAll($sql);
    }

    // =========================
    // TÉCNICO
    // =========================
    public function findByTecnico(int $tecnicoId): array
    {
        $sql = "SELECT i.*, 
                       u.nombre AS cliente_nombre,
                       u.telefono AS cliente_telefono,
                       e.nombre_estado,
                       s.nombre_especialidad
                FROM incidencias i
                JOIN usuarios u ON i.cliente_id = u.id
                JOIN estados e ON i.estado_id = e.id
                JOIN especialidades s ON i.especialidad_id = s.id
                WHERE i.tecnico_id = :tecnico_id
                ORDER BY i.fecha_servicio ASC";

        return $this->fetchAll($sql, [
            'tecnico_id' => $tecnicoId
        ]);
    }

    public function findById(int $id): array|false
    {
        $sql = "SELECT i.*, 
                       u.nombre AS cliente_nombre,
                       u.telefono AS cliente_telefono
                FROM incidencias i
                JOIN usuarios u ON i.cliente_id = u.id
                WHERE i.id = :id";

        return $this->fetch($sql, ['id' => $id]);
    }

    // 🔥 FIX: cancelación con segundos (no diff()->days)
    public function cancel(int $id, int $clienteId): bool
    {
        $incidencia = $this->findById($id);

        if (!$incidencia || $incidencia['cliente_id'] != $clienteId) {
            return false;
        }

        $fechaServicio = new DateTime($incidencia['fecha_servicio']);
        $ahora = new DateTime();

        $diffSegundos = $fechaServicio->getTimestamp() - $ahora->getTimestamp();

        if ($diffSegundos < 172800) {
            return false;
        }

        $estadoCancelado = $this->getEstadoIdByNombre('Cancelada');

        $sql = "UPDATE incidencias 
                SET estado_id = :estado_id 
                WHERE id = :id AND cliente_id = :cliente_id";

        return $this->execute($sql, [
            'estado_id' => $estadoCancelado,
            'id' => $id,
            'cliente_id' => $clienteId
        ]);
    }

    public function cancelAdmin(int $id): bool
    {
        $estadoCancelado = $this->getEstadoIdByNombre('Cancelada');

        $sql = "UPDATE incidencias 
                SET estado_id = :estado_id 
                WHERE id = :id";

        return $this->execute($sql, [
            'estado_id' => $estadoCancelado,
            'id' => $id
        ]);
    }

    public function assignTecnico(int $incidenciaId, int $tecnicoId): bool
    {
        $estadoAsignado = $this->getEstadoIdByNombre('Asignada');

        $sql = "UPDATE incidencias 
                SET tecnico_id = :tecnico_id, estado_id = :estado_id 
                WHERE id = :id";

        return $this->execute($sql, [
            'tecnico_id' => $tecnicoId,
            'estado_id' => $estadoAsignado,
            'id' => $incidenciaId
        ]);
    }

    public function updateAdmin(int $id, array $data): bool
    {
        $sql = "UPDATE incidencias SET
                    especialidad_id = :especialidad_id,
                    estado_id = :estado_id,
                    descripcion = :descripcion,
                    direccion = :direccion,
                    fecha_servicio = :fecha_servicio,
                    tipo_urgencia = :tipo_urgencia
                WHERE id = :id";

        return $this->execute($sql, [
            'id' => $id,
            'especialidad_id' => $data['especialidad_id'],
            'estado_id' => $data['estado_id'],
            'descripcion' => $data['descripcion'],
            'direccion' => $data['direccion'],
            'fecha_servicio' => $data['fecha_servicio'],
            'tipo_urgencia' => $data['tipo_urgencia']
        ]);
    }

    public function getEstados(): array
    {
        return $this->fetchAll("SELECT * FROM estados");
    }
}