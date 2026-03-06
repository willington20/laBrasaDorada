-- ================================================
-- BASE DE DATOS: La Brasa Dorada
-- CRUD: Platos + Reservas + Usuarios
-- ================================================

CREATE DATABASE IF NOT EXISTS brasa_dorada CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE brasa_dorada;

-- ================================================
-- TABLA: usuarios (administradores del sistema)
-- ================================================
CREATE TABLE usuarios (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    rol         ENUM('admin', 'mesero') DEFAULT 'mesero',
    activo      TINYINT(1) DEFAULT 1,
    creado_en   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================================
-- TABLA: platos (menu del restaurante)
-- ================================================
CREATE TABLE platos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio      DECIMAL(10,2) NOT NULL,
    categoria   ENUM('entradas','principales','postres','bebidas') NOT NULL,
    disponible  TINYINT(1) DEFAULT 1,
    creado_en   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================================
-- TABLA: reservas
-- ================================================
CREATE TABLE reservas (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    telefono    VARCHAR(20) NOT NULL,
    fecha       DATE NOT NULL,
    hora        TIME NOT NULL,
    personas    INT NOT NULL,
    notas       TEXT,
    estado      ENUM('pendiente','confirmada','cancelada') DEFAULT 'pendiente',
    creado_en   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================================
-- DATOS DE EJEMPLO
-- ================================================

-- Usuario admin (password: admin123)
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Administrador', 'admin@brasa.co', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Juan Mesero', 'juan@brasa.co', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mesero');

-- Platos del menu
INSERT INTO platos (nombre, descripcion, precio, categoria) VALUES
('Ensalada de la Casa',  'Lechugas frescas, tomates cherry y aderezo de limon', 18000, 'entradas'),
('Sopa de Tomate',       'Crema suave con albahaca y pan artesanal',              22000, 'entradas'),
('Lomo a la Brasa',      'Lomo de res con chimichurri y papas rusticas',          65000, 'principales'),
('Trucha al Limon',      'Trucha al horno con mantequilla y hierbas',             58000, 'principales'),
('Pollo en Salsa',       'Pechuga en salsa de maracuya con arroz y platano',      45000, 'principales'),
('Flan de Caramelo',     'Flan artesanal con caramelo dorado',                    16000, 'postres'),
('Volcan de Chocolate',  'Bizcocho con centro liquido y helado de vainilla',      20000, 'postres'),
('Limonada de Coco',     'Limonada con leche de coco y menta fresca',             12000, 'bebidas');

-- Reservas de ejemplo
INSERT INTO reservas (nombre, telefono, fecha, hora, personas, estado) VALUES
('Carlos Ramirez', '3001234567', CURDATE(), '19:00:00', 4, 'confirmada'),
('Maria Lopez',    '3109876543', CURDATE(), '20:30:00', 2, 'pendiente'),
('Luis Torres',    '3157894561', DATE_ADD(CURDATE(), INTERVAL 1 DAY), '13:00:00', 6, 'pendiente');
