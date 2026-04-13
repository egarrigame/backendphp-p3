<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/Model.php';

class Tecnico extends Model
{
    protected string $table = 'tecnicos';

    public function getAll(): array
    {
        $sql = "SELECT 
                    t.id,
                    t.nombre_completo,
                    t.especialidad_id,
                    t.disponible,
                    e.nombre_especialidad
                FROM tecnicos t
                LEFT JOIN especialidades e ON t.especialidad_id = e.id
                ORDER BY t.nombre_completo ASC";

        return $this->fetchAll($sql);
    }

    public function findById(int $id): array|false
    {
        $sql = "SELECT 
                    t.id,
                    t.nombre_completo,
                    t.especialidad_id,
                    t.disponible,
                    e.nombre_especialidad
                FROM tecnicos t
                LEFT JOIN especialidades e ON t.especialidad_id = e.id
                WHERE t.id = :id
                LIMIT 1";

        return $this->fetch($sql, ['id' => $id]);
    }

    public function findByUsuarioId(int $usuarioId): array|false
    {
        $sql = "SELECT * FROM tecnicos WHERE usuario_id = :usuario_id LIMIT 1";
        return $this->fetch($sql, ['usuario_id' => $usuarioId]);
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO tecnicos (usuario_id, nombre_completo, especialidad_id, disponible)
                VALUES (:usuario_id, :nombre_completo, :especialidad_id, :disponible)";

        return $this->execute($sql, [
            'usuario_id' => $data['usuario_id'] ?? null,
            'nombre_completo' => $data['nombre_completo'],
            'especialidad_id' => $data['especialidad_id'],
            'disponible' => $data['disponible'] ?? 1
        ]);
    }

    public function update(int $id, array $data): bool
    {
        // Si no viene nombre_completo, lo recuperamos de BD
        if (!isset($data['nombre_completo']) || empty($data['nombre_completo'])) {
            $tecnico = $this->findById($id);

            if (!$tecnico) {
                return false;
            }

            $data['nombre_completo'] = $tecnico['nombre_completo'];
        }

        $sql = "UPDATE tecnicos 
                SET nombre_completo = :nombre_completo,
                    especialidad_id = :especialidad_id,
                    disponible = :disponible
                WHERE id = :id";

        return $this->execute($sql, [
            'id' => $id,
            'nombre_completo' => $data['nombre_completo'],
            'especialidad_id' => $data['especialidad_id'],
            'disponible' => $data['disponible']
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM tecnicos WHERE id = :id";
        return $this->execute($sql, ['id' => $id]);
    }

    public function getAgenda(int $tecnicoId): array
    {
        $sql = "SELECT 
                    i.id,
                    i.localizador,
                    i.direccion,
                    i.descripcion,
                    i.fecha_servicio,
                    i.tipo_urgencia,
                    u.nombre AS cliente_nombre,
                    e.nombre_especialidad,
                    es.nombre_estado
                FROM incidencias i
                JOIN usuarios u ON i.cliente_id = u.id
                JOIN especialidades e ON i.especialidad_id = e.id
                JOIN estados es ON i.estado_id = es.id
                WHERE i.tecnico_id = :tecnico_id
                ORDER BY i.fecha_servicio ASC";

        return $this->fetchAll($sql, ['tecnico_id' => $tecnicoId]);
    }
}