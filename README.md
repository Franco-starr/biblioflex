# Biblioflex

Sistema de gestión de biblioteca desarrollado en PHP vanilla. Permite administrar un catálogo de libros, categorías y préstamos con sistema de roles (admin/usuario).

## Tecnologías utilizadas

- **PHP 8+** — Vanilla, sin framework
- **MySQL 8.0** — Base de datos relacional
- **CSS personalizado** — Dark theme, diseño responsive
- **JavaScript vanilla** — Comportamiento de sidebar y drawer
- **Composer** — Autoloading PSR-4
- **Intervention Image v2.7** — Manejo de imágenes (portadas de libros)
- **ActiveRecord** — ORM custom para modelos

## Funcionalidades

### Públicas
- Catálogo de libros con búsqueda y filtros por categoría
- Vista detallada de cada libro
- Registro e inicio de sesión con email

### Admin
- Panel de administración con dashboard
- CRUD completo de libros (crear, editar, eliminar con imagen)
- CRUD completo de categorías
- Gestión de préstamos (listado, devolución)
- Control de stock de libros
- Reasignación automática de libros al eliminar una categoría

### Usuario
- Solicitud de préstamos de libros
- Vista de "Mis Préstamos" con estado y fechas

### Diseño responsive
- Sidebar colapsable en desktop (hover/click)
- Header con drawer lateral en móvil (< 768px)
- Grid de libros 2 columnas en móvil
- Tablas con tarjetas apiladas en móvil
- Detalle de libro apilado verticalmente en móvil

## Configuración de la base de datos

### Requisitos
- PHP 8.0 o superior
- MySQL 8.0 o superior
- XAMPP, Laragon o similar

### Pasos

1. Crear la base de datos:
```sql
CREATE DATABASE biblioflex;
```

2. Importar los archivos SQL en orden:
```sql
-- Opción A: Por consola
mysql -u root -p biblioflex < sql/biblioflex_categoria.sql
mysql -u root -p biblioflex < sql/biblioflex_permisos.sql
mysql -u root -p biblioflex < sql/biblioflex_libros.sql
mysql -u root -p biblioflex < sql/biblioflex_prestamo.sql

-- Opción B: Importar desde MySQL Workbench o phpMyAdmin
```

3. Renombrar columna para login con email:
```sql
ALTER TABLE usuario CHANGE usuario email VARCHAR(100) NOT NULL;
```

4. Instalar dependencias de Composer:
```bash
composer install
```

### Credenciales de la base de datos

Las credenciales se encuentran en el archivo `.env` en la raíz del proyecto. Crealo con las siguientes variables:

```
DB_HOST=localhost
DB_USER=root
DB_PASS=root
DB_NAME=biblioflex
```

> **Importante:** El archivo `.env` ya está incluido en `.gitignore` y no se sube al repositorio. Si tus credenciales de MySQL son diferentes, editalo directamente.

## Estructura del proyecto

```
biblioflex/
├── admin/                  # Panel de administración
│   ├── categoria/          # CRUD de categorías
│   ├── libros/             # CRUD de libros
│   ├── prestamos/          # Gestión de préstamos
│   └── usuario/            # (pendiente)
├── includes/
│   ├── config/
│   │   └── database.php    # Conexión a la base de datos
│   ├── templates/
│   │   ├── header.php      # Cabecera HTML + sidebar
│   │   ├── navbar.php      # Sidebar de navegación
│   │   └── footer.php      # Pie de página
│   ├── app.php             # Bootstrap de la aplicación
│   └── funciones.php       # Funciones helper (auth, XSS, etc.)
├── models/                 # Modelos ActiveRecord
│   ├── ActiveRecord.php    # Clase base ORM
│   ├── Libro.php
│   ├── Categoria.php
│   └── Prestamo.php
├── public/                 # Páginas públicas
│   ├── assets/
│   │   ├── css/style.css   # Estilos principales
│   │   └── js/style.js     # Lógica de sidebar
│   ├── prestamos/          # Préstamos del usuario
│   ├── index.php           # Catálogo de libros
│   ├── libro.php           # Detalle de libro
│   ├── login.php           # Inicio de sesión
│   ├── register.php        # Registro de usuario
│   └── logout.php          # Cierre de sesión
├── sql/                    # Archivos SQL de la BD
└── composer.json
```

## Autor

**Franco** — franco.star.dev@gmail.com
