# 🍽️ La Brasa Dorada — Restaurante


<img width="1674" height="812" alt="image" src="https://github.com/user-attachments/assets/c3e5fa1a-3267-4c0a-9e92-81d2aac4713c" />


Página web completa de restaurante con panel de administración CRUD.

<img width="1725" height="719" alt="image" src="https://github.com/user-attachments/assets/6193a2f6-40ec-44b8-9f47-b955041ac228" />

## 🛠️ Tecnologías
- HTML + CSS + JavaScript (Frontend)
- PHP 8.2 + Apache (Backend)
- MySQL 8.0 (Base de datos)
- Docker + Docker Compose

## 🚀 Cómo correr el proyecto

### Requisitos
- Instalar [Docker Desktop](https://www.docker.com/products/docker-desktop/)



<img width="944" height="323" alt="image" src="https://github.com/user-attachments/assets/1db0e04a-fad7-4846-a4db-b861914f7330" />


### Pasos

**1. Clona el repositorio**
```bash
git clone https://github.com/willington20/laBrasaDorada/
```



**2. Levanta los contenedores muy IMPORTANTE **
```bash
ingresa al contenedor con el siguiente comando:

cd /workspaces/laBrasaDorada/labrasadorada
docker compose up -d --build

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
