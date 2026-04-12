<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/Model.php';

class User extends Model
{
    protected string $table = 'usuarios';

    public function findByEmail(string $email): array|false
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        return $this->fetch($sql, ['email' => $email]);
    }

    public function findById(int $id): array|false
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        return $this->fetch($sql, ['id' => $id]);
    }

    public function create(array $data): bool
    {
        if ($this->findByEmail($data['email'])) {
            return false;
        }

        $sql = "
            INSERT INTO {$this->table} 
            (nombre, email, password, rol, telefono)
            VALUES (:nombre, :email, :password, :rol, :telefono)
        ";

        return $this->execute($sql, [
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'password' => $data['password'],
            'rol' => $data['rol'] ?? 'particular',
            'telefono' => $data['telefono']
        ]);
    }

    // Perfil
    public function updateProfile(int $id, array $data): bool
    {
        $fields = [];
        $params = ['id' => $id];

        // Nombre
        if (!empty($data['nombre'])) {
            $fields[] = "nombre = :nombre";
            $params['nombre'] = $data['nombre'];
        }

        // Email (validación + duplicado)
        if (!empty($data['email'])) {

            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return false;
            }

            $existing = $this->findByEmail($data['email']);

            if ($existing && (int)$existing['id'] !== $id) {
                return false;
            }

            $fields[] = "email = :email";
            $params['email'] = $data['email'];
        }

        // Teléfono
        if (!empty($data['telefono'])) {
            $fields[] = "telefono = :telefono";
            $params['telefono'] = $data['telefono'];
        }

        // Password (si se envía, se hashea aquí)
        if (!empty($data['password'])) {
            $fields[] = "password = :password";
            $params['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";

        return $this->execute($sql, $params);
    }

        public function getClientes(): array
    {
        $sql = "SELECT id, nombre, email FROM {$this->table} WHERE rol = 'particular'";
        return $this->fetchAll($sql);
    }
}