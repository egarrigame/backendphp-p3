CREATE DATABASE IF NOT EXISTS reparaya;
USE reparaya;

-- 1. USUARIOS
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'tecnico', 'particular') NOT NULL DEFAULT 'particular',
    telefono VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. ESPECIALIDADES
CREATE TABLE especialidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_especialidad VARCHAR(50) NOT NULL
);

-- 3. ESTADOS (ampliación)
CREATE TABLE estados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_estado VARCHAR(50) NOT NULL
);

-- 4. TECNICOS
CREATE TABLE tecnicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNIQUE,
    nombre_completo VARCHAR(100) NOT NULL,
    especialidad_id INT,
    disponible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    FOREIGN KEY (especialidad_id) REFERENCES especialidades(id)
);

-- 5. INCIDENCIAS (sin ENUM)
CREATE TABLE incidencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    localizador VARCHAR(12) NOT NULL UNIQUE,
    cliente_id INT NOT NULL,
    tecnico_id INT DEFAULT NULL,
    especialidad_id INT NOT NULL,
    estado_id INT DEFAULT 1,
    descripcion TEXT NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    fecha_servicio DATETIME NOT NULL,
    tipo_urgencia ENUM('Estándar', 'Urgente') DEFAULT 'Estándar',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id),
    FOREIGN KEY (tecnico_id) REFERENCES tecnicos(id),
    FOREIGN KEY (especialidad_id) REFERENCES especialidades(id),
    FOREIGN KEY (estado_id) REFERENCES estados(id)
);

-- índices
CREATE INDEX idx_cliente ON incidencias(cliente_id);
CREATE INDEX idx_tecnico ON incidencias(tecnico_id);

-- datos iniciales

INSERT INTO usuarios (nombre, email, password, rol)
VALUES (
    'Admin',
    'admin@reparaya.com',
    '$2y$10$wH6z6vJz3b1VvZrXrYlE2uC7xX5s9j3y6YpZ1cQzQ5n1Xz8YwZ9mK',
    'admin'
);

INSERT INTO especialidades (nombre_especialidad) VALUES
('Fontanería'),
('Electricidad'),
('Aire acondicionado');

INSERT INTO estados (nombre_estado) VALUES
('Pendiente'),
('Asignada'),
('Finalizada'),
('Cancelada');