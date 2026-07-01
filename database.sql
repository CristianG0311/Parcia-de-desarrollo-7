-- =========================================================
-- Parcial Práctico I - Base de datos parcial_itech
-- Crear en http://127.1.1.1/phpmyadmin/
-- =========================================================
CREATE DATABASE IF NOT EXISTS parcial_itech CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE parcial_itech;

-- Tabla de países (sirve tanto para país de residencia como nacionalidad)
CREATE TABLE IF NOT EXISTS paises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    UNIQUE KEY (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO paises (nombre) VALUES
('Panamá'),('Colombia'),('Costa Rica'),('México'),('Estados Unidos'),
('España'),('Argentina'),('Chile'),('Perú'),('Venezuela');

-- Tabla de áreas de interés tecnológico
CREATE TABLE IF NOT EXISTS areas_interes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    UNIQUE KEY (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO areas_interes (nombre) VALUES
('Desarrollo Web'),('Inteligencia Artificial'),('Ciberseguridad'),
('Desarrollo Móvil'),('Cloud Computing'),('Big Data'),
('IoT (Internet de las Cosas)'),('Blockchain'),('DevOps'),('Machine Learning'),
('Python');

-- Tabla principal de inscriptores
-- Restricciones de contacto a nivel de BD: correo UNIQUE + formato validado en PHP,
-- celular UNIQUE y con longitud controlada.
CREATE TABLE IF NOT EXISTS inscriptores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identidad VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    edad INT NOT NULL,
    sexo ENUM('Masculino', 'Femenino', 'Otro') NOT NULL,
    pais_residencia_id INT NOT NULL,
    nacionalidad_id INT NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    celular VARCHAR(20) NOT NULL UNIQUE,
    observaciones TEXT,
    firma_integridad VARCHAR(255) DEFAULT NULL, -- firma OpenSSL (hash HMAC) del registro
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_pais_residencia FOREIGN KEY (pais_residencia_id)
        REFERENCES paises(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_nacionalidad FOREIGN KEY (nacionalidad_id)
        REFERENCES paises(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla intermedia (muchos a muchos) inscriptor <-> áreas de interés
CREATE TABLE IF NOT EXISTS inscriptor_temas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    inscriptor_id INT NOT NULL,
    area_interes_id INT NOT NULL,

    CONSTRAINT fk_inscriptor FOREIGN KEY (inscriptor_id)
        REFERENCES inscriptores(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_area_interes FOREIGN KEY (area_interes_id)
        REFERENCES areas_interes(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    UNIQUE KEY uq_inscriptor_area (inscriptor_id, area_interes_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
