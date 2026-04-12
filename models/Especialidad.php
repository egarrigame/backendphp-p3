<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/Model.php';

class Especialidad extends Model
{
    protected string $table = 'especialidades';

    public function getAll(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY nombre_especialidad ASC";
        return $this->fetchAll($sql);
    }

    public function findById(int $id): array|false
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        return $this->fetch($sql, ['id' => $id]);
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO {$this->table} (nombre_especialidad)
                VALUES (:nombre_especialidad)";

        return $this->execute($sql, [
            'nombre_especialidad' => $data['nombre_especialidad']
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table}
                SET nombre_especialidad = :nombre_especialidad
                WHERE id = :id";

        return $this->execute($sql, [
            'id' => $id,
            'nombre_especialidad' => $data['nombre_especialidad']
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->execute($sql, ['id' => $id]);
    }
}