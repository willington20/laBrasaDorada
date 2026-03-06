# 🍽️ La Brasa Dorada — Restaurante

Página web completa de restaurante con panel de administración CRUD.

## 🛠️ Tecnologías
- HTML + CSS + JavaScript (Frontend)
- PHP 8.2 + Apache (Backend)
- MySQL 8.0 (Base de datos)
- Docker + Docker Compose

## 🚀 Cómo correr el proyecto

### Requisitos
- Instalar [Docker Desktop](https://www.docker.com/products/docker-desktop/)

### Pasos

**1. Clona el repositorio**
```bash
git clone https://github.com/TU_USUARIO/restaurante.git
cd restaurante
```

**2. Levanta los contenedores**
```bash
docker-compose up -d
```

**3. Abre en el navegador**

| URL | Descripción |
|-----|-------------|
| http://localhost:8080 | Página web del restaurante |
| http://localhost:8080/admin.php | Panel de administración |
| http://localhost:8081 | phpMyAdmin (base de datos) |

**4. Para detener**
```bash
docker-compose down
```

## 📁 Estructura del proyecto
```
restaurante/
├── index.html          ← Página principal
├── admin.php           ← Panel CRUD
├── conexion.php        ← Conexión a MySQL
├── database.sql        ← Base de datos
├── Dockerfile          ← Configuración PHP+Apache
├── docker-compose.yml  ← Orquestación de servicios
└── README.md
```

## ✅ Funcionalidades
- 🌙 Modo claro / oscuro
- 🛒 Carrito de pedidos
- ✅ Validación de formulario de reservas
- 📊 Panel admin con CRUD completo
- 🍽️ Gestión de platos del menú
- 📅 Gestión de reservas
- 👤 Gestión de usuarios con roles